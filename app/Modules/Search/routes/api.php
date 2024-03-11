<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v2/search'], function () {
//    Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/by-site', \App\Modules\Search\Controllers\SearchBySiteController::class);
//    });
});


