<?php

Route::resource('/metering-devices/register', 'Admin\Bookkeeping\MeteringDevices\DeviceRegisterController')
    ->only(['index', 'update', 'store']);

// выставить счет по id показания
Route::post('/billing/insrumet-readings/add-invoice/{id}', 'Admin\Bookkeeping\Billing\InstrumentReadings\AddInvoiceController@index');
// выставить счет группе показаний
Route::post('/billing/insrumet-readings/add-group-invoice', 'Admin\Bookkeeping\Billing\InstrumentReadings\AddGroupInvoiceController@index');
