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


Route::group(['prefix' => 'v2/telegram'], function () {
//    Route::get('get-user-info', \App\Modules\Telegram\Controllers\GetUserInfoController::class);
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('get-bot-info', \App\Modules\Telegram\Controllers\GetBotInfoController::class);
        Route::get('get-last-user-from-telegram', \App\Modules\Telegram\Controllers\GetLastMessageUserIdController::class);
        Route::get('setting/get-telegram-bot-token', \App\Modules\Telegram\Controllers\GetTelegramTokenController::class);
        Route::post('setting/update-telegram-bot-token', \App\Modules\Telegram\Controllers\UpdateTelegramTokenController::class);
        Route::post('setting/change-two-factor-enable', \App\Modules\Telegram\Controllers\ChangeTwoFactorEnableController::class);
    });
});