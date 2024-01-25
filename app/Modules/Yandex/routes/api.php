<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v2/yandex'], function () {
    Route::get('schedule/get', \App\Modules\Yandex\YandexSchedule\Controllers\ScheduleController::class);
    Route::get('map/get-steads', App\Modules\Yandex\YandexMap\Controllers\MapController::class);
});