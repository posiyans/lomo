<?php


Route::get('user-info', 'App\Http\Controllers\UserController@info');
Route::resource('user', 'App\Http\Controllers\Admin\UserController')
    ->only(['index', 'show', 'update']);
Route::resource('role', 'App\Http\Controllers\Admin\RoleController')
    ->only(['index', 'update']);


Route::get('role-list', 'App\Http\Controllers\Admin\UserController@roleList');

Route::get('user/profile/send-verify-mail-token/{id}', 'App\Http\Controllers\Admin\UserController@sendVerifyMailToken');
