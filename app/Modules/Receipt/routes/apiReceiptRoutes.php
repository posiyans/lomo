<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v2/receipt'], function () {
//    Route::group(['middleware' => ['auth:sanctum']], function () {
//        Route::post('update', \App\Modules\Gardening\Controllers\UpdateGardeningInfoController::class);
//    });
    Route::get('qrcode', \App\Modules\Receipt\Controllers\GetQrCodeForGardeningController::class);
    Route::get('get-clear', \App\Modules\Receipt\Controllers\ReceiptClearController::class); // получить пустую квитанцию
});

