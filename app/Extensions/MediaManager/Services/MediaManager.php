<?php
namespace App\Extensions\MediaManager\Services;

use Carbon\Carbon;
use TalvBansal\MediaManager\Contracts\UploadedFilesInterface;
use TalvBansal\MediaManager\Services\MediaManager as BaseMediaManager;
use Dflydev\ApacheMimeTypes\PhpRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

/**
 * Class MediaManager.
 */
class MediaManager extends BaseMediaManager
{
    /**
     * @var string
     */
    public $diskName = '';
    
    /**
     * @var array
     */
    private $errors = [];
    
    /**
     * UploadsManager constructor.
     *
     * @param PhpRepository $mimeDetect
     */
    public function __construct(PhpRepository $mimeDetect)
    {
        $this->diskName = 'qiniu';
        $this->disk = Storage::disk($this->diskName);
        $this->mimeDetect = $mimeDetect;
    }

    /**
     * Show all directories that the selected item can be moved to.
     *
     * @return array
     */
    public function allDirectories()
    {
        return collect(['/' => 'Root']);
    }

    /**
     * Return files and directories within a folder.
     *
     * @param string $folder
     *
     * @return array of [
     *               'folder' => 'path to current folder',
     *               'folderName' => 'name of just current folder',
     *               'breadCrumbs' => breadcrumb array of [ $path => $foldername ],
     *               'subfolders' => array of [ $path => $foldername] of each subfolder,
     *               'files' => array of file details on each file in folder,
     *               'itemsCount' => a combined count of the files and folders within the current folder
     *               ]
     */
    public function folderInfo($folder = '/')
    {
        $folder = $this->cleanFolder($folder);
        $breadCrumbs = $this->breadcrumbs($folder);
        $folderName = $breadCrumbs->pop();
        
        // Get the names of the sub folders within this folder
        $subFolders = collect([]);
        
        // Get all files within this folder
        $files = collect($this->disk->allFiles($folder))->reduce(function ($files, $path) {
            // Don't show hidden files or folders
            if (!starts_with(last(explode(DIRECTORY_SEPARATOR, $path)), '.')) {
                $files[] = $this->fileDetails($path);
            }
            
            return $files;
        }, collect([]));
        
        $itemsCount = $subFolders->count() + $files->count();
        
        return compact('folder', 'folderName', 'breadCrumbs', 'subFolders', 'files', 'itemsCount');
    }

    /**
     * Return an array of file details for a file.
     *
     * @param $path
     *
     * @return array
     */
    protected function fileDetails($path)
    {
        $path = '/'.ltrim($path, '/');
        
        return [
            'name'         => basename($path),
            'fullPath'     => $path,
            'webPath'      => $this->fileWebpath($path),
            'mimeType'     => $this->fileMimeType($path),
            'size'         => $this->fileSize($path),
            'modified'     => $this->fileModified($path),
            'relativePath' => $this->fileRelativePath($path),
        ];
    }
    
    /**
     * Return the full web path to a file.
     *
     * @param $path
     *
     * @return string
     */
    public function fileWebpath($path)
    {
        $path = $this->fileRelativePath($path);
        
        return 'http://' . config("filesystems.disks.{$this->diskName}.domain") . $path;
    }
    
    /**
     * @param $path
     *
     * @return string
     */
    private function fileRelativePath($path)
    {
        $path = str_replace(' ', '%20', $path);
        
        return '/'.ltrim($path, '/');
    }
    
    /**
     * Return the last modified time.
     *
     * @param $path
     *
     * @return Carbon
     */
    public function fileModified($path)
    {
        return Carbon::createFromTimestamp($this->disk->lastModified($path) / 10000000);
    }
    
    /**
     * This method will take a collection of files that have been
     * uploaded during a request and then save those files to
     * the given path.
     *
     * @param UploadedFilesInterface $files
     * @param string                 $path
     *
     * @return int
     */
    public function saveUploadedFiles(UploadedFilesInterface $files, $path = '/')
    {
        return $files->getUploadedFiles()->reduce(function ($uploaded, UploadedFile $file) use ($path) {
            $fileName = $file->getClientOriginalName();
            if ($this->disk->exists($path.$fileName)) {
                $this->errors[] = 'File '.$path.$fileName.' already exists in this folder.';
                
                return $uploaded;
            }
            
            if (!$file->storeAs($path, $fileName, 'qiniu')) {
                $this->errors[] = trans('media-manager::messages.upload_error', ['entity' => $fileName]);
                
                return $uploaded;
            }
            $uploaded++;
            
            return $uploaded;
        }, 0);
    }
}