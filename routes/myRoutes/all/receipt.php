<?php
// пустая квитанция
//Route::get('receipt/get-receipt-clear', 'PdfController@clearReceipt');
Route::get('receipt/get-clear', 'All\Receipt\ReceiptClearController@index');
Route::get('receipt/get-receipt-type-list', 'All\Receipt\GetReceiptTypeListController@index');

Route::post('receipt/get-receipt-contributions-for-stead', 'All\ReceiptController@getContributionsReceipt');
// пустой QR code
Route::get('receipt/get-qrcode-clear', 'QrCodeController@qrCodeClear');
// получить квитанцию по переданным параметрам
Route::post('receipt/get-receipt-for-params', 'All\Receipt\GetReceiptForParamsController@index');
//показания
// получить список приборов для получнеия показаний
Route::post('instrument-reading/get-device-for-stead', 'All\InstrumentReading\GetDeviceForSteadController@index');
// отправить показания по приборам
Route::post('instrument-reading/send-data-for-stead', 'All\InstrumentReading\SendDataForSteadController@index');


