<?php



Route::resource('category', 'App\Http\Controllers\Admin\CategoryController')
    ->only(['index', 'update', 'store']);
Route::resource('article', 'App\Http\Controllers\Admin\ArticleController')
    ->only(['index', 'update', 'store', 'show']);


Route::resource('comment', 'App\Http\Controllers\User\CommentController')
    ->only(['store', 'destroy']);


//Route::resource('voting', 'Admin\AdminVotingController')
//    ->only(['index', 'update', 'store', 'show']);
//Route::resource('voting-result', 'Admin\AdminVotingResultController')
//    ->only(['index', 'update', 'store', 'show']);
