<?php


Route::resource('stead', 'Admin\SteadController')
    ->only(['index', 'show', 'update']);
Route::resource('rate', 'Admin\RateController')
    ->only(['index', 'update', 'store']);

Route::resource('gardening', 'User\GardeningController')
    ->only(['store']); //todo нге работает заглушка
