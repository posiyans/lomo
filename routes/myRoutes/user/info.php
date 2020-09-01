<?php

//Route::get('/', 'UserController@info');
Route::post('save-user-info', 'UserController@save');
Route::post('vote', 'User\UserVotingController@addAnswer');

// Перенесено в all
//Route::post('steads-list', 'User\SteadController@list');
Route::get('info', 'UserController@info');
