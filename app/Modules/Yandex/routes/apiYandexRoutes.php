<?php

use Illuminate\Support\Facades\Route;


//Route::group(, function () {
Route::get('schedule/get', \App\Modules\Yandex\YandexSchedule\Controllers\ScheduleController::class);
//Route::get('map/get', 'App\Modules\Yandex\YandexMap\Controllers\MapController@getMap');
//});