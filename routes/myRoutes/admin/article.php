<?php



Route::resource('category', 'Admin\CategoryController')
    ->only(['index', 'update', 'store']);
Route::resource('article', 'Admin\ArticleController')
    ->only(['index', 'update', 'store', 'show']);


Route::resource('comment', 'User\CommentController')
    ->only(['store', 'destroy']);


Route::resource('voting', 'Admin\AdminVotingController')
    ->only(['index', 'update', 'store', 'show']);
Route::resource('voting-result', 'Admin\AdminVotingResultController')
    ->only(['index', 'update', 'store', 'show']);
