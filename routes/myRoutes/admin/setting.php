<?php

// настройка тарифов и типов платежей
Route::resource('/setting/receipt-type', 'App\Http\Controllers\Admin\Setting\AdminReceiptTypeController')
    ->only(['index', 'update', 'store', 'show']);
Route::resource('/setting/rate', 'App\Http\Controllers\Admin\Setting\AdminRateController')
    ->only(['index', 'update', 'store']);

// настройка камер
Route::get('/setting/camera/get-list', 'App\Http\Controllers\Admin\Setting\Camera\AdminCameraGetListController@index');
Route::post('/setting/camera/add', 'App\Http\Controllers\Admin\Setting\Camera\AdminCameraAddController@index');
Route::post('/setting/camera/update', 'App\Http\Controllers\Admin\Setting\Camera\AdminCameraUpdateController@index');
