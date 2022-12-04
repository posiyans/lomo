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
Route::get('/api/v2/csrf-cookie', [Laravel\Sanctum\Http\Controllers\CsrfCookieController::class, 'show']);

Route::get('/api/v2/user/get-avatar/{uid}', [\App\Modules\User\Controllers\GetUserAvatarController::class, 'index']);
Route::get('/api/v2/user/info', [\App\Modules\User\Controllers\Auth\GetMyInfoController::class, 'index']);
Route::post('/api/v2/login', [\App\Modules\User\Controllers\Auth\LoginController::class, 'login']);
Route::post('/api/v2/register', [\App\Modules\User\Controllers\Auth\RegisterController::class, 'register']);
Route::get('/api/v2/password-reset', [\App\Modules\User\Controllers\Auth\ResetPasswordController::class, 'reset']);
Route::post('/api/v2/password-send-reset-link', [\App\Modules\User\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::get('/api/v2/logout', [\App\Modules\User\Controllers\Auth\LogoutController::class, 'logout']);

//Route::post('/api/v2/article/user-add', [\App\Modules\Article\Controllers\UserArticleEditController::class, 'addArticle']);
//Route::get('/api/v2/article/get-files', [\App\Modules\Article\Controllers\GetFilesForArticleController::class, 'index']);
//
//Route::get('/api/v2/status/get-list', [\App\Modules\Article\Controllers\StatusGetListController::class, 'index']);
