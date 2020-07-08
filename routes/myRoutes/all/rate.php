<?php

Route::resource('user/rate', 'User\RateController')
    ->only(['index']);
Route::resource('user/gardening', 'User\GardeningController')
    ->only(['index']);
