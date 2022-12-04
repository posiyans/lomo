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

Route::get('/api/v2/vk/get-auth-path', [\App\Modules\Social\Vk\Controllers\GetPathAuthController::class, 'index']);
Route::get('/api/vk/auth/callback', [\App\Modules\Social\Vk\Controllers\CallbackController::class, 'index']);

