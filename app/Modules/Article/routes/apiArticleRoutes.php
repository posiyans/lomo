<?php

use Illuminate\Support\Facades\Route;


Route::get('/v2/article/get-files', [\App\Modules\Article\Controllers\GetFilesForArticleController::class, 'index']);
Route::get('/v2/status/get-list', [\App\Modules\Article\Controllers\StatusGetListController::class, 'index']);


Route::group(['prefix' => 'v2/article'], function () {
    Route::post('user-add', \App\Modules\Article\Controllers\Article\UserAddArticleController::class);
    Route::get('get-list', \App\Modules\Article\Controllers\Article\GetArtileListForCategoryController::class);
    Route::get('get/{slug}', [\App\Modules\Article\Controllers\Article\UserGetArticleController::class, 'index']);
});

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'v2/article/admin'], function () {
    Route::get('get-list', [\App\Modules\Article\Controllers\Article\AdminGetArticleListController::class, 'index']);
    Route::get('get/{id}', [\App\Modules\Article\Controllers\Article\AdminGetArticleController::class, 'index']);
    Route::put('update/{id}', [\App\Modules\Article\Controllers\Article\UpdateArticleController::class, 'index']);
});

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'v2/category'], function () {
    Route::post('update/{category}', \App\Modules\Article\Controllers\Category\UpdateCategoryController::class);
    Route::post('create', \App\Modules\Article\Controllers\Category\CreateCategoryController::class);
    Route::get('get-list', \App\Modules\Article\Controllers\Category\GetListCategoryController::class);
});