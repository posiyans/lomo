<?php

Route::get('temper', 'App\Http\Controllers\TemperController@index');
Route::get('temper/get', 'App\Http\Controllers\TemperController@showGrafTemper');
Route::get('temper/getNewWeatherProHD', 'App\Http\Controllers\TemperController@getNewWeatherProHD');
Route::get('temper/getNowWeatherProHD', 'App\Http\Controllers\TemperController@getNowWeatherProHD');
//Route::get('/api/temper/get', 'TemperController@showGrafTemper');
Route::post('temper/now', 'App\Http\Controllers\TemperController@showLocalTemper'); // old
