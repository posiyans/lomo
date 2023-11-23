<?php


Route::get('user-info', 'App\Http\Controllers\UserController@info');
Route::apiResource('user', 'App\Http\Controllers\Admin\UserController');


Route::get('user/profile/send-verify-mail-token/{id}', 'App\Http\Controllers\Admin\UserController@sendVerifyMailToken');
