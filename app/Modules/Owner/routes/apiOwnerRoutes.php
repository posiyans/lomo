<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v2/owner'], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('create', \App\Modules\Owner\Controllers\CreateOwnerUserController::class);
        Route::get('get-field-list', \App\Modules\Owner\Controllers\GetOwnerFieldListController::class);
    });
//    Route::get('get-qrcode', \App\Modules\Gardening\Controllers\GetGardeningInfoController::class);
});

