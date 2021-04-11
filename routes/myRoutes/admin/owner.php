<?php


//Route::get('user-info', 'App\Http\Controllers\UserController@info');
Route::resource('owner-resource', 'App\Http\Controllers\Admin\Owner\OwnerResourceController')
    ->only(['index', 'show', 'update', 'store', 'destroy']);
Route::get('owner/list/download/xlsx', 'App\Http\Controllers\Admin\Owner\OwnerResourceController@ownerListXlsx');
Route::get('owner/fields-list', 'App\Http\Controllers\Admin\Owner\OwnerModelFieldsController@getFields');

Route::post('owner/owner-add-stead', 'App\Http\Controllers\Admin\Owner\OwnerUserController@add');
//Route::resource('role', 'App\Http\Controllers\Admin\RoleController')
//    ->only(['index', 'update']);
//
//
//Route::get('role-list', 'App\Http\Controllers\Admin\UserController@roleList');
//
//Route::get('user/profile/send-verify-mail-token/{id}', 'App\Http\Controllers\Admin\UserController@sendVerifyMailToken');
