<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v2/metering-device'], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('get-for-stead/{stead}', \App\Modules\MeteringDevice\Controllers\GetMeteringDevicesForSteadController::class);
        Route::post('/{device}', \App\Modules\MeteringDevice\Controllers\UpdateFieldMeteringDeviceController::class);
        Route::post('/', \App\Modules\MeteringDevice\Controllers\CreateMeteringDeviceController::class);
        Route::delete('/{device}', \App\Modules\MeteringDevice\Controllers\DeleteMeteringDeviceController::class);
    });
//    Route::get('get-qrcode', \App\Modules\Gardening\Controllers\GetGardeningInfoController::class);
});

Route::group(['prefix' => 'v2/instrument-reading'], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('get-list', \App\Modules\MeteringDevice\Controllers\GetInstrumentReadingListController::class);
        Route::post('/', \App\Modules\MeteringDevice\Controllers\AddInstrumentReadingController::class);
        Route::delete('/{reading}', \App\Modules\MeteringDevice\Controllers\DeleteInstrumentReadingController::class);
    });
});

