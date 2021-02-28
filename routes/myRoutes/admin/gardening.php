<?php

Route::resource('gardening', 'App\Http\Controllers\User\GardeningController')
    ->only(['store']); //todo нге работает заглушка
