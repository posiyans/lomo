<?php

//Route::get('/', 'UserController@info');
Route::post('save-user-info', 'UserController@save');
Route::post('vote', 'User\UserVotingController@addAnswer');
Route::get('steads-list', 'User\SteadController@list');
Route::get('info', 'UserController@info');
