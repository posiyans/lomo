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

Route::get('/api/v2/avatar/get-user', [\App\Modules\Avatar\Controllers\GetUserAvatarController::class, 'index']);
//Route::get('/api/v2/file/get', [\App\Modules\File\Controllers\GetFileController::class, 'index']);

