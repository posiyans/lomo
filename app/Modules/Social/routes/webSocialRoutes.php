<?php

Route::group(['prefix' => 'vk'], function () {
    Route::get('/auth/callback', \App\Modules\Social\vendor\Vk\Controllers\CallbackController::class);
//    Route::post('/auth/get-path', \App\Modules\Social\vendor\Vk\Controllers\GetPathAuthController::class);
});