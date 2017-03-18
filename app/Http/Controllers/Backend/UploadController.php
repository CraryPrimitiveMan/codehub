<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    /**
     * Show the Media Library.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('canvas::backend.upload.index');
    }
}