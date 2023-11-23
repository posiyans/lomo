<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v2/auth'], function () {
    Route::post('login', [\App\Modules\Auth\Controllers\LoginController::class, 'login']);
    Route::post('register', [\App\Modules\Auth\Controllers\RegisterController::class, 'register']);
    Route::post('password-send-reset-link', [\App\Modules\Auth\Controllers\ForgotPasswordController::class, 'sendResetLinkEmail']);
//    Route::get('mail_verify/{id}', [\App\Modules\Auth\Controllers\MailVerifyController::class, 'verify'])->name('api.verification.verify');
});


Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'v2/auth'], function () {
    Route::get('info', \App\Modules\Auth\Controllers\GetMyInfoController::class);
    Route::post('send-verify-email', \App\Modules\Auth\Controllers\SendMailVerifyCodeController::class);
    Route::get('logout', \App\Modules\Auth\Controllers\LogoutController::class);
});






