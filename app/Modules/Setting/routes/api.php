<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v2/setting'], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('favicon/update', \App\Modules\Setting\Controllers\ChangeFaviconController::class);
    });
});


