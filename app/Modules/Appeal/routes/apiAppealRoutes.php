<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v2/appeal'], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('type/get-list', \App\Modules\Appeal\Controlles\GetAppealTypeController::class);
        Route::put('type/{appealType}', \App\Modules\Appeal\Controlles\UpdateAppealTypeController::class);
        Route::delete('type/{appealType}', \App\Modules\Appeal\Controlles\DeleteAppealTypeController::class);
        Route::get('get/{appeal}', \App\Modules\Appeal\Controlles\GetAppealController::class);
        Route::get('get-list', \App\Modules\Appeal\Controlles\GetAppealListController::class);
        Route::post('create', \App\Modules\Appeal\Controlles\CreateAppealController::class);
        Route::post('close/{appeal}', \App\Modules\Appeal\Controlles\CloseAppealController::class);
    });
});
