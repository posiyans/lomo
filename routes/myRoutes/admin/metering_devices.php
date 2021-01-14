<?php

Route::resource('/metering-devices/register', 'Admin\Bookkeeping\MeteringDevices\DeviceRegisterController')
    ->only(['index', 'update', 'store']);
