<?php


Route::get('user-info', 'UserController@info');
Route::resource('user', 'Admin\UserController')
    ->only(['index', 'show', 'update']);
Route::resource('role', 'Admin\RoleController')
    ->only(['index', 'update']);


Route::get('role-list', 'Admin\UserController@roleList');

Route::get('user/profile/send-verify-mail-token/{id}', 'Admin\UserController@sendVerifyMailToken');
