<?php

Route::resource('user/rate', 'App\Http\Controllers\User\RateController')
    ->only(['index']);
Route::resource('user/gardening', 'App\Http\Controllers\User\GardeningController')
    ->only(['index']);
