<?php
// обращения
Route::get('appeal/info', 'UserController@info');
Route::resource('appeal', 'Admin\AppealController')
    ->only(['index', 'show', 'update']);
