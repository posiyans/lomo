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


