<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v2/auth'], function () {
    Route::get('mail_verify/{id}', [\App\Modules\Auth\Controllers\MailVerifyController::class, 'verify'])->name('api.verification.verify');
    Route::post('password-change', [\App\Modules\Auth\Controllers\ResetPasswordController::class, 'reset']);
});


