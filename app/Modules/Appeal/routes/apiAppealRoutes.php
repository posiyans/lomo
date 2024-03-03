<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v2/appeal'], function () {
    Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'type'], function () {
        Route::get('get-list', \App\Modules\Appeal\Controlles\GetAppealTypeController::class);
        Route::put('{appealType}', \App\Modules\Appeal\Controlles\UpdateAppealTypeController::class);
        Route::post('/', \App\Modules\Appeal\Controlles\CreateAppealTypeController::class);
        Route::delete('{appealType}', \App\Modules\Appeal\Controlles\DeleteAppealTypeController::class);
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('get/{appeal}', \App\Modules\Appeal\Controlles\GetAppealController::class);
        Route::get('get-list', \App\Modules\Appeal\Controlles\GetAppealListController::class);
        Route::post('create', \App\Modules\Appeal\Controlles\CreateAppealController::class);
        Route::post('close/{appeal}', \App\Modules\Appeal\Controlles\CloseAppealController::class);
        Route::put('/{appeal}', \App\Modules\Appeal\Controlles\UpdateAppealController::class);
    });
});
