<?php

Route::resource('user/category', 'User\CategoryController')->only(['index']);
Route::resource('user/article', 'User\ArticleController')->only(['index', 'show']);
Route::resource('user/comment', 'User\CommentController')->only(['index']);
Route::resource('user/voting', 'User\Voting\UserVotingController')->only(['index', 'show']);
Route::post('vote', 'User\Voting\UserVotingController@addAnswer');
