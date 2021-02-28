<?php
// пустая квитанция
//Route::get('receipt/get-receipt-clear', 'PdfController@clearReceipt');
Route::get('receipt/get-clear', 'App\Http\Controllers\All\Receipt\ReceiptClearController@index');
Route::get('receipt/get-receipt-type-list', 'App\Http\Controllers\All\Receipt\GetReceiptTypeListController@index');

Route::post('receipt/get-receipt-contributions-for-stead', 'App\Http\Controllers\All\ReceiptController@getContributionsReceipt');
// пустой QR code
Route::get('receipt/get-qrcode-clear', 'App\Http\Controllers\QrCodeController@qrCodeClear');
// получить квитанцию по переданным параметрам
Route::post('receipt/get-receipt-for-params', 'App\Http\Controllers\All\Receipt\GetReceiptForParamsController@index');
//показания
// получить список приборов для получнеия показаний
Route::post('instrument-reading/get-device-for-stead', 'App\Http\Controllers\All\InstrumentReading\GetDeviceForSteadController@index');
// отправить показания по приборам
Route::post('instrument-reading/send-data-for-stead', 'App\Http\Controllers\All\InstrumentReading\SendDataForSteadController@index');


