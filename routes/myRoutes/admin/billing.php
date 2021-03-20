<?php
// бухгалтерия
Route::get('billing/balance-info', 'App\Http\Controllers\Admin\Bookkeeping\Billing\BalanceController@info');
Route::get('billing/balance-list', 'App\Http\Controllers\Admin\Bookkeeping\Billing\BalanceController@list');
Route::post('billing/balance-xlsx', 'App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\GetXlsxFileController@index');
Route::get('billing/balance-for-stead', 'App\Http\Controllers\Admin\Bookkeeping\Billing\BalanceController@allBalance');

Route::resource('billing/reestr', 'App\Http\Controllers\Admin\Bookkeeping\Billing\ReestrController')
    ->only(['index', 'show', 'update', 'store', 'destroy']);

Route::resource('billing/payment', 'App\Http\Controllers\Admin\Bookkeeping\Billing\PaymentController')
    ->only(['index', 'show', 'update', 'store']);
// счета
Route::post('billing/change-invoice/add-payment', 'App\Http\Controllers\Admin\Bookkeeping\Billing\Invoice\ChangeStatusInvoiceController@addPaymentForInvoice');
Route::post('billing/change-invoice/delete-payment', 'App\Http\Controllers\Admin\Bookkeeping\Billing\Invoice\ChangeStatusInvoiceController@deletePaymentForInvoice');
Route::post('billing/change-invoice/change-status', 'App\Http\Controllers\Admin\Bookkeeping\Billing\Invoice\ChangeStatusInvoiceController@changeStatus');
Route::resource('billing/invoice', 'App\Http\Controllers\Admin\Bookkeeping\Billing\InvoiceController')
    ->only(['index', 'show', 'update', 'store']);

Route::post('billing/bank-reestr/upload', 'App\Http\Controllers\Admin\Bookkeeping\Billing\BankReestrController@uploadReestr');
Route::get('billing/bank-reestr-info', 'App\Http\Controllers\Admin\Bookkeeping\Billing\BankReestrController@info');
Route::get('billing/bank-reestr-list', 'App\Http\Controllers\Admin\Bookkeeping\Billing\BankReestrController@list');
Route::post('billing/bank-reestr-update', 'App\Http\Controllers\Admin\Bookkeeping\Billing\BankReestrController@update');
Route::post('billing/bank-reestr-publish', 'App\Http\Controllers\Admin\Bookkeeping\Billing\BankReestrController@publish');
Route::post('billing/bank-reestr-parse', 'App\Http\Controllers\Admin\Bookkeeping\Billing\BankReestrController@parseReestr');
Route::post('billing/bank-reestr-publish', 'App\Http\Controllers\Admin\Bookkeeping\Billing\BankReestrController@publish');

// Электроэнергия
Route::get('billing/communal/stead/get/{id}', 'App\Http\Controllers\Admin\Bookkeeping\Communal\ElectroSteadController@list');
Route::post('billing/communal/stead/add-reading/{id}', 'App\Http\Controllers\Admin\Bookkeeping\Communal\ElectroSteadController@addInstrumentReadings');


