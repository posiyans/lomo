<?php

//Route::resource('user/category', 'User\CategoryController')->only(['index']);
//Route::resource('user/article', 'User\ArticleController')->only(['index', 'show']);
//Route::resource('user/comment', 'User\CommentController')->only(['index']);
Route::resource('user/voting', 'App\Http\Controllers\User\Voting\UserVotingController')->only(['index', 'show']);
Route::post('vote', 'App\Http\Controllers\User\Voting\UserVotingController@addAnswer');
Route::post('user/voting/sms/send', 'App\Http\Controllers\User\Voting\UserVotingSmsController@send');
Route::post('user/voting/sms/valid', 'App\Http\Controllers\User\Voting\UserVotingSmsController@valid');
Route::post('user/voting/file/upload', 'App\Http\Controllers\User\Voting\UserVotingFileUploadController@upload');
Route::get('user/voting/question/result/get', 'App\Http\Controllers\User\Voting\UserVotingResultController@index');
