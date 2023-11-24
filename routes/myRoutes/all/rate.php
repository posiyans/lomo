<?php

Route::resource('user/rate', 'App\Http\Controllers\User\RateController')
    ->only(['index']);

