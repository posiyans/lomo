<?php

// настройка тарифов и типов платежей
Route::resource('/setting/receipt-type', 'Admin\Setting\AdminReceiptTypeController')
    ->only(['index', 'update', 'store', 'show']);
Route::resource('/setting/rate', 'Admin\Setting\AdminRateController')
    ->only(['index', 'update', 'store']);
