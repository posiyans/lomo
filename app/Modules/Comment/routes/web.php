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

Route::post('/api/v2/comment/send-message', [\App\Modules\Comment\Controllers\SendMessageController::class, 'index']);
Route::get('/api/v2/comment/get-message', [\App\Modules\Comment\Controllers\GetMessageController::class, 'index']);

