<?php
// квитанции
Route::post('receipt/get-for-stead', 'App\Http\Controllers\Admin\ReceiptController@getReceipt');
Route::post('receipt/get-for-list-steads', 'App\Http\Controllers\Admin\ReceiptController@getReceiptForListStead');
//Route::post('receipt/get-reestr-for-list-steads', 'Admin\ReceiptController@getReestrForListStead');


Route::get('receipt/get-list', 'App\Http\Controllers\Admin\Receipt\ReceiptListController@index');
Route::get('receipt/get-for-invoices', 'App\Http\Controllers\Admin\Receipt\GetReceiptForInvoicesController@index');
