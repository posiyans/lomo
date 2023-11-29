<?php


Route::resource('owner-resource', 'App\Http\Controllers\Admin\Owner\OwnerResourceController')
    ->only(['index', 'show', 'update', 'store', 'destroy']);
Route::get('owner/list/download/xlsx', 'App\Http\Controllers\Admin\Owner\OwnerResourceController@ownerListXlsx');

Route::post('owner/owner-add-stead', 'App\Http\Controllers\Admin\Owner\OwnerUserController@add');
Route::delete('owner/delete-stead/{id}', 'App\Http\Controllers\Admin\Owner\OwnerUserSteadResourceController@destroy');

