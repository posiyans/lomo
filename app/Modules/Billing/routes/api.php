<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v2/billing'], function () {
    Route::group(['prefix' => 'invoice'], function () {
        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::get('/get-list', \App\Modules\Billing\Controllers\Invoice\GetInvoiceListController::class);
            Route::delete('/delete/{invoice}', \App\Modules\Billing\Controllers\Invoice\DeleteInvoiceController::class);
        });
    });
    Route::group(['prefix' => 'payment'], function () {
        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::get('/get-list', \App\Modules\Billing\Controllers\Payment\GetPaymentListController::class);
            Route::post('/{payment}', \App\Modules\Billing\Controllers\Payment\UpdatePaymentController::class);
            Route::get('/{payment}', \App\Modules\Billing\Controllers\Payment\GetPaymentController::class);
            Route::post('/', \App\Modules\Billing\Controllers\Payment\AddPaymentController::class);
//            Route::delete('/delete/{invoice}', \App\Modules\Billing\Controllers\Invoice\DeleteInvoiceController::class);
        });
    });
    Route::group(['middleware' => ['auth:sanctum']], function () {
    });
});




