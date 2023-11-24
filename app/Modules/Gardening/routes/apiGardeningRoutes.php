<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v2/gardening'], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('update', \App\Modules\Gardening\Controllers\UpdateGardeningInfoController::class);
    });
    Route::get('get', \App\Modules\Gardening\Controllers\GetGardeningInfoController::class);
    Route::get('get-qrcode', \App\Modules\Gardening\Controllers\GetGardeningInfoController::class);
});

