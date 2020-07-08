<?php

Route::get('temper', 'TemperController@index');
Route::get('temper/get', 'TemperController@showGrafTemper');
Route::get('temper/getNewWeatherProHD', 'TemperController@getNewWeatherProHD');
Route::get('temper/getNowWeatherProHD', 'TemperController@getNowWeatherProHD');
//Route::get('/api/temper/get', 'TemperController@showGrafTemper');
Route::post('temper/now', 'TemperController@showLocalTemper'); // old
