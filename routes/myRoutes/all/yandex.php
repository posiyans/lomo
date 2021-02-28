<?php



Route::get('yandex/rasp', 'App\Http\Controllers\Yandex\ScheduleController@index');
Route::get('yandex/get-map', 'App\Http\Controllers\Yandex\MapController@getMap');

