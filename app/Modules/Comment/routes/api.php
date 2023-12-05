<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v2/comment'], function () {
    Route::get('/get-message', [\App\Modules\Comment\Controllers\GetMessagesController::class, 'index']);
});

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'v2/comment'], function () {
    Route::get('/get-all', \App\Modules\Comment\Controllers\GetAllMessageListController::class);
    Route::post('/send-message', [\App\Modules\Comment\Controllers\SendMessageController::class, 'index']);
    Route::delete('/delete', [\App\Modules\Comment\Controllers\DeleteMessageController::class, 'index']);
});
