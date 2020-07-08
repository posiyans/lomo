<?php




//маршруты авторизации и регистрации
Route::post('/register', 'Auth\RegisterController@register');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::post('login', 'Auth\LoginController@login');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('api.verification.verify');
Route::post('login', 'Auth\LoginController@apiLogin');
Route::post('login-vk', 'VkController@getUrl');
Route::post('user-logout', 'Auth\LoginController@logout');
Route::get('user', 'UserController@info');


