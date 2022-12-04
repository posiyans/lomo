<?php
//define('__ROOT__', dirname(__FILE__));
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/api/v1/category/get-list', [\App\Modules\Article\Controllers\CategoryGetListController::class, 'index']);

Route::post('/api/v2/article/user-add', [\App\Modules\Article\Controllers\UserArticleEditController::class, 'addArticle']);
Route::get('/api/v2/article/get-files', [\App\Modules\Article\Controllers\GetFilesForArticleController::class, 'index']);

Route::get('/api/v2/status/get-list', [\App\Modules\Article\Controllers\StatusGetListController::class, 'index']);
