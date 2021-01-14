<?php

//Route::resource('user/category', 'User\CategoryController')->only(['index']);
//Route::resource('user/article', 'User\ArticleController')->only(['index', 'show']);
//Route::resource('user/comment', 'User\CommentController')->only(['index']);
Route::resource('user/voting', 'User\Voting\UserVotingController')->only(['index', 'show']);
Route::post('vote', 'User\Voting\UserVotingController@addAnswer');
Route::post('user/voting/sms/send', 'User\Voting\UserVotingSmsController@send');
Route::post('user/voting/sms/valid', 'User\Voting\UserVotingSmsController@valid');
Route::post('user/voting/file/upload', 'User\Voting\UserVotingFileUploadController@upload');
Route::post('user/voting/question/result/get', 'User\Voting\UserVotingResultController@index');
