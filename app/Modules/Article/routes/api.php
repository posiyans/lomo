<?php

use Illuminate\Support\Facades\Route;


Route::get('/v2/article/get-files', [\App\Modules\Article\Controllers\GetFilesForArticleController::class, 'index']);
Route::get('/v2/status/get-list', [\App\Modules\Article\Controllers\StatusGetListController::class, 'index']);


Route::group(['prefix' => 'v2/article'], function () {
    Route::post('user-add', \App\Modules\Article\Controllers\Article\UserAddArticleController::class);
    Route::get('get-list', \App\Modules\Article\Controllers\Article\GetArtileListForCategoryController::class);
    Route::get('get/{slug}', [\App\Modules\Article\Controllers\Article\UserGetArticleController::class, 'index']);

    Route::group(['prefix' => 'setting'], function () {
        Route::get('allow-publication', \App\Modules\Article\Controllers\Settings\GetAllowPublicationArticleController::class);
        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::post('allow-publication/update', \App\Modules\Article\Controllers\Settings\ChangeAllowPublicationArticleController::class);
        });
    });
});

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'v2/article/admin'], function () {
    Route::post('create', \App\Modules\Article\Controllers\Article\CreateArticleController::class);
    Route::get('get-list', [\App\Modules\Article\Controllers\Article\AdminGetArticleListController::class, 'index']);
    Route::get('get/{article}', \App\Modules\Article\Controllers\Article\AdminGetArticleController::class);
    Route::put('update/{article}', \App\Modules\Article\Controllers\Article\UpdateArticleController::class);
});

Route::group(['prefix' => 'v2/category'], function () {
    Route::get('get-list', \App\Modules\Article\Controllers\Category\GetListCategoryController::class);
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('update/{category}', \App\Modules\Article\Controllers\Category\UpdateCategoryController::class);
        Route::post('create', \App\Modules\Article\Controllers\Category\CreateCategoryController::class);
    });
});