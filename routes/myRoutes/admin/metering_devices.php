<?php

Route::resource('/metering-devices/register', 'App\Http\Controllers\Admin\Bookkeeping\MeteringDevices\DeviceRegisterController')
    ->only(['index', 'update', 'store']);

// выставить счет по id показания
Route::post('/billing/insrumet-readings/add-invoice/{id}', 'App\Http\Controllers\Admin\Bookkeeping\Billing\InstrumentReadings\AddInvoiceController@index');
// выставить счет группе показаний
Route::post('/billing/insrumet-readings/add-group-invoice', 'App\Http\Controllers\Admin\Bookkeeping\Billing\InstrumentReadings\AddGroupInvoiceController@index');
