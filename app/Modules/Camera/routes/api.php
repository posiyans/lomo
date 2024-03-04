<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v2/camera'], function () {
    Route::get('/get-list', \App\Modules\Camera\Controllers\CameraListController::class);
    Route::get('/get-image/{id}', \App\Modules\Camera\Controllers\GetCameraImageController::class);
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/reload/{id}', \App\Modules\Camera\Controllers\ReloadCameraImageController::class);
        Route::post('/create', \App\Modules\Camera\Controllers\CreateCameraController::class);
        Route::post('/{id}', \App\Modules\Camera\Controllers\UpdateCameraController::class);
    });
});


//Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'v2/avatar'], function () {
//    Route::post('/user/upload', [\App\Modules\Avatar\Controllers\UploadUserAvatarController::class, 'index']);
//});