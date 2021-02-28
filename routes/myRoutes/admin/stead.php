<?php


Route::resource('stead', 'App\Http\Controllers\Admin\AdminSteadController')
    ->only(['index', 'show', 'update']);
