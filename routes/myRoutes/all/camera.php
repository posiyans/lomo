<?php

// получить картинку с камеры (для cron)

Route::get('camera/all-list', 'App\Http\Controllers\All\Camera\UserCameraController@list');
Route::get('camera', 'App\Http\Controllers\Camera\CameraController@index');
// вывести последнюю картинку
Route::get('camera/img/{id?}', 'App\Http\Controllers\Camera\CameraController@getImages');
Route::get('all/camera/get-image/{id?}', 'App\Http\Controllers\All\Camera\UserCameraController@getImage');

//    склесть gif из картинок с камеры (на будующее)
Route::get('camera/create-gif/{token?}', 'App\Http\Controllers\Camera\CameraController@createGif');
Route::get('camera/test', 'App\Http\Controllers\HomeController@test');
