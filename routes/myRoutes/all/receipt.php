<?php
// пустая квитанция
//Route::get('receipt/get-receipt-clear', 'PdfController@clearReceipt');
Route::get('receipt/get-clear', 'All\Receipt\ReceiptClearController@index');
Route::get('receipt/get-receipt-type-list', 'All\Receipt\GetReceiptTypeListController@index');

Route::post('receipt/get-receipt-contributions-for-stead', 'All\ReceiptController@getContributionsReceipt');
// пустой QR code
Route::get('receipt/get-qrcode-clear', 'QrCodeController@qrCodeClear');
