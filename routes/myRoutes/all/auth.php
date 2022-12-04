<?php




//маршруты авторизации и регистрации
Route::post('/register', 'App\Http\Controllers\Auth\RegisterController@register');
Route::post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset');
Route::post('login', 'App\Http\Controllers\Auth\LoginController@login');
Route::get('email/verify/{id}', 'App\Http\Controllers\Auth\VerificationController@verify')->name('api.verification.verify');
//Route::post('login', 'Auth\LoginController@apiLogin');
//Route::post('login-vk', 'App\Http\Controllers\VkController@getUrl');
Route::post('user-logout', 'App\Http\Controllers\Auth\LoginController@logout');
Route::get('user', 'App\Http\Controllers\UserController@info');


