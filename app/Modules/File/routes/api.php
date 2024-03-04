<?php
//define('__ROOT__', dirname(__FILE__));
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => 'v2/file'], function () {
    Route::group([], function () {
        Route::get('get', [\App\Modules\File\Controllers\GetFileController::class, 'index']);
    });
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('upload', [\App\Modules\File\Controllers\UserUploadController::class, 'index']);
        Route::delete('delete', [\App\Modules\File\Controllers\DeleteFileController::class, 'index']);
    });
});