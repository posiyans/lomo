<?php

Route::group(['prefix' => 'v2/social'], function () {
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/get-list', \App\Modules\Social\Controllers\GetSocialMediaListController::class);
    });
    Route::group(['prefix' => 'vk'], function () {
        Route::post('/auth/get-path', \App\Modules\Social\vendor\Vk\Controllers\GetPathAuthController::class);
    });
});
