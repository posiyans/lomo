<?php

// настройка тарифов и типов платежей
Route::resource('/setting/receipt-type', 'App\Http\Controllers\Admin\Setting\AdminReceiptTypeController')
    ->only(['index', 'update', 'store', 'show']);
Route::resource('/setting/rate', 'App\Http\Controllers\Admin\Setting\AdminRateController')
    ->only(['index', 'update', 'store']);
