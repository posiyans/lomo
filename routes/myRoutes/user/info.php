<?php

//Route::get('/', 'UserController@info');
Route::post('save-user-info', 'App\Http\Controllers\UserController@save');
Route::post('vote', 'App\Http\Controllers\User\Voting\UserVotingController@addAnswer');

// Перенесено в all
//Route::post('steads-list', 'User\SteadController@list');
Route::get('info', 'App\Http\Controllers\UserController@info');
