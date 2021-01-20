<?php

Route::resource('/metering-devices/register', 'Admin\Bookkeeping\MeteringDevices\DeviceRegisterController')
    ->only(['index', 'update', 'store']);


Route::post('/billing/insrumet-readings/add-invoice/{id}', 'Admin\Bookkeeping\Billing\InstrumentReadings\AddInvoiceController@index');
