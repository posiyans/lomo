<?php

Route::resource('user/category', 'App\Http\Controllers\User\CategoryController')->only(['index']);
Route::resource('user/article', 'App\Http\Controllers\User\ArticleController')->only(['index', 'show']);
Route::resource('user/comment', 'App\Http\Controllers\User\CommentController')->only(['index']);
//Route::resource('user/voting', 'User\Voting\UserVotingController')->only(['index', 'show']);
//Route::post('vote', 'User\Voting\UserVotingController@addAnswer');
