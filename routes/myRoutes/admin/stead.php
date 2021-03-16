<?php


Route::resource('stead', 'App\Http\Controllers\Admin\AdminSteadController')
    ->only(['index', 'show', 'update']);

Route::get('/stead/get-owner/{id}', 'App\Http\Controllers\Admin\Stead\GetOwnerController@index');
