<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v2/receipt'], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('get-receipt-for-invoice', \App\Modules\Receipt\Controllers\GetReceiptForInvoiceController::class);
    });
    Route::get('get-receipt-for-stead', \App\Modules\Receipt\Controllers\GetReceiptForSteadController::class);
    Route::get('qrcode', \App\Modules\Receipt\Controllers\GetQrCodeForGardeningController::class);
    Route::get('get-clear', \App\Modules\Receipt\Controllers\ReceiptClearController::class); // получить пустую квитанцию
});

