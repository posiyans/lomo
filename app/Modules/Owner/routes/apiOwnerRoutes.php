<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v2/owner'], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('create', \App\Modules\Owner\Controllers\CreateOwnerUserController::class);
        Route::get('get-field-list', \App\Modules\Owner\Controllers\GetOwnerFieldListController::class);
        Route::get('get-list', \App\Modules\Owner\Controllers\GetOwnerUserListController::class);
        Route::get('get/{owner}', \App\Modules\Owner\Controllers\GetOwnerUserInfoController::class);
        Route::post('update/{owner}', \App\Modules\Owner\Controllers\UpdateOwnerUserFieldController::class);
        Route::post('add-stead', \App\Modules\Owner\Controllers\AddSteadToOwnerUserController::class);
    });
//    Route::get('get-qrcode', \App\Modules\Gardening\Controllers\GetGardeningInfoController::class);
});


