<?php

// получить картинку с камеры (для cron)

Route::get('camera', 'App\Http\Controllers\Camera\CameraController@index');
// вывести последнюю картинку
Route::get('camera/img/{id?}', 'App\Http\Controllers\Camera\CameraController@getImages');

//    склесть gif из картинок с камеры (на будующее)
Route::get('camera/create-gif/{token?}', 'App\Http\Controllers\Camera\CameraController@createGif');
Route::get('camera/test', 'App\Http\Controllers\HomeController@test');
