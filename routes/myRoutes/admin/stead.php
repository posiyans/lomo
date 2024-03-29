<?php


Route::resource('stead', 'App\Http\Controllers\Admin\Stead\AdminSteadResourceController')
    ->only(['index', 'show', 'update']);

Route::get('/stead/get-file/xlsx', 'App\Http\Controllers\Admin\Stead\AdminSteadsListToFileController@getXlsx');
Route::get('/stead/get-owner/{id}', 'App\Http\Controllers\Admin\Stead\GetOwnerController@index');
