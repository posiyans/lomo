<?php


Route::resource('stead', 'Admin\AdminSteadController')
    ->only(['index', 'show', 'update']);
