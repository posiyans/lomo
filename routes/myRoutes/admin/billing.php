<?php
// бухгалтерия
Route::get('billing/balance-info', 'Admin\Bookkeeping\Billing\BalanceController@info');
Route::get('billing/balance-list', 'Admin\Bookkeeping\Billing\BalanceController@list');
Route::resource('billing/reestr', 'Admin\Bookkeeping\Billing\ReestrController')
    ->only(['index', 'show', 'update', 'store']);

Route::resource('billing/payment', 'Admin\Bookkeeping\Billing\PaymentController')
    ->only(['index', 'show', 'update', 'store']);
Route::resource('billing/invoice', 'Admin\Bookkeeping\Billing\InvoiceController')
    ->only(['index', 'show', 'update', 'store']);

Route::post('billing/bank-reestr/upload', 'Admin\Bookkeeping\Billing\BankReestrController@uploadReestr');
Route::get('billing/bank-reestr-info', 'Admin\Bookkeeping\Billing\BankReestrController@info');
Route::get('billing/bank-reestr-list', 'Admin\Bookkeeping\Billing\BankReestrController@list');
Route::post('billing/bank-reestr-update', 'Admin\Bookkeeping\Billing\BankReestrController@update');
Route::post('billing/bank-reestr-publish', 'Admin\Bookkeeping\Billing\BankReestrController@publish');
Route::post('billing/bank-reestr-parse', 'Admin\Bookkeeping\Billing\BankReestrController@parseReestr');
Route::post('billing/bank-reestr-publish', 'Admin\Bookkeeping\Billing\BankReestrController@publish');
