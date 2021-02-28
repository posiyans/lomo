<?php
// обращения
Route::get('appeal/info', 'App\Http\Controllers\UserController@info');
Route::resource('appeal', 'App\Http\Controllers\Admin\AppealController')
    ->only(['index', 'show', 'update']);
