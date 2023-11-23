<?php

use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'v2/ban-user'], function () {
    Route::get('/get-for-user', \App\Modules\BanUser\Controllers\GetBanForUserController::class);
    Route::get('/check', \App\Modules\BanUser\Controllers\CheckUserBanController::class);
    Route::post('/add', \App\Modules\BanUser\Controllers\AddUserBanController::class);
    Route::get('/list', \App\Modules\BanUser\Controllers\GetUserBanListController::class);
    Route::delete('/delete', \App\Modules\BanUser\Controllers\DeleteUserBanController::class);
    Route::put('/update', \App\Modules\BanUser\Controllers\UpdateUserBanController::class);
});




