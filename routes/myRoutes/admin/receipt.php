<?php
// квитанции
Route::post('receipt/get-for-stead', 'Admin\ReceiptController@getReceipt');
Route::post('receipt/get-for-list-steads', 'Admin\ReceiptController@getReceiptForListStead');
//Route::post('receipt/get-reestr-for-list-steads', 'Admin\ReceiptController@getReestrForListStead');


Route::get('receipt/get-list', 'Admin\Receipt\ReceiptListController@index');
Route::get('receipt/get-for-invoices', 'Admin\Receipt\GetReceiptForInvoicesController@index');
