<?php

// получить картинку с камеры (для cron)
Route::get('camera', 'Camera\CameraController@index');
// вывести последнюю картинку
Route::get('camera/img/{id?}', 'Camera\CameraController@getImages');
//    склесть gif из картинок с камеры (на будующее)
Route::get('camera/create-gif/{token?}', 'Camera\CameraController@createGif');
Route::get('camera/test', 'HomeController@test');
