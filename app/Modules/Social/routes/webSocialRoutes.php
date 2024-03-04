<?php

Route::group(['prefix' => 'vk'], function () {
    Route::get('/auth/callback', \App\Modules\Social\vendor\Vk\Controllers\CallbackController::class);
});