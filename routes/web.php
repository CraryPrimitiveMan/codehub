<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
use Canvas\Helpers\RouteHelper;

/* Backend page routes. */
Route::group([
    'middlewareGroups' => RouteHelper::getGeneralMiddlewareGroups(),
    'middleware' => RouteHelper::getAdminMiddleware(),
    'namespace' => 'Backend',
], function () {
    /* Post page routes. */
    Route::resource(RouteHelper::getAdminPrefix().'/post', 'PostController', [
        'except' => 'show',
        'names'  => [
            'index'   => 'canvas.admin.post.index',
            'create'  => 'canvas.admin.post.create',
            'store'   => 'canvas.admin.post.store',
            'edit'    => 'canvas.admin.post.edit',
            'update'  => 'canvas.admin.post.update',
            'destroy' => 'canvas.admin.post.destroy',
        ],
    ]);

    // Media Manager Routes
    Route::get('/admin/browser/index', 'MediaController@ls');
    
    Route::post('admin/browser/file', 'MediaController@uploadFiles');
    Route::delete('/admin/browser/delete', 'MediaController@deleteFile');
    Route::post('/admin/browser/folder', 'MediaController@createFolder');
    Route::delete('/admin/browser/folder', 'MediaController@deleteFolder');
    
    Route::post('/admin/browser/rename', 'MediaController@rename');
    Route::get('/admin/browser/directories', 'MediaController@allDirectories');
    Route::post('/admin/browser/move', 'MediaController@move');
});