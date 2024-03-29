<?php
// бухгалтерия
use App\Http\Controllers\Admin\Bookkeeping\Billing\InstrumentReadings\DeleteReadingController;
use App\Http\Controllers\Admin\Bookkeeping\Billing\InstrumentReadings\ReadingForSteadController;
use App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\BalanceForStead\BalanceForSteadXlsxFileController;
// получить баланс по участку  (счета и плтежи)
Route::get('billing/balance-info', 'App\Http\Controllers\Admin\Bookkeeping\Billing\BalanceController@info');
// получить баланс по участку  (счета и плтежи) в xlsx
Route::get('billing/balance-for-stead-xlsx', [BalanceForSteadXlsxFileController::class, 'index']);

Route::get('billing/balance-list', 'App\Http\Controllers\Admin\Bookkeeping\Billing\BalanceController@list');
Route::post('billing/balance-xlsx', 'App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\BalanceList\GetXlsxFileController@index');
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

// получить показания по участку
Route::get('billing/communal/stead/get/{stead}', [ReadingForSteadController::class, 'list'])
    ->missing(function () {
        return response()->json([
            'status' => false,
            'error' => 'Обьект не найден'
        ], 200);
    });

//удалить показания
Route::delete('billing/communal/stead/delete-reading/{reading}', [DeleteReadingController::class, 'delete'])
    ->missing(function () {
        return response()->json([
            'status' => false,
            'error' => 'Обьект не найден'
        ], 200);
    });

Route::post('billing/communal/stead/add-reading/{id}', 'App\Http\Controllers\Admin\Bookkeeping\Communal\ElectroSteadController@addInstrumentReadings');


