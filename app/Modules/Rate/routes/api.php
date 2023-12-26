<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v2/rate'], function () {
    Route::get('get/{group}', \App\Modules\Rate\Controllers\GetRateListController::class);
    Route::group(['prefix' => 'group'], function () {
        Route::get('get-list', \App\Modules\Rate\Controllers\GetRateGroupListController::class);

        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::post('create', \App\Modules\Rate\Controllers\CreateRateGroupController::class);
        });
    });
});


