<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v2/avatar'], function () {
    Route::get('/user/get', [\App\Modules\Avatar\Controllers\GetUserAvatarController::class, 'index']);
});


Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'v2/avatar'], function () {
    Route::post('/user/upload', [\App\Modules\Avatar\Controllers\UploadUserAvatarController::class, 'index']);
});