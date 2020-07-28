<?php
// квитанции
Route::post('receipt/get-for-stead', 'Admin\ReceiptController@getReceipt');
Route::post('receipt/get-for-list-steads', 'Admin\ReceiptController@getReceiptForListStead');
Route::post('receipt/get-reestr-for-list-steads', 'Admin\ReceiptController@getReestrForListStead');
