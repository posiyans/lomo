<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v2/site-menu'], function () {
    Route::get('/get', \App\Modules\SiteMenu\Controllers\GetSiteMenuController::class);
    Route::post('/add', \App\Modules\SiteMenu\Controllers\AddSiteMenuController::class);
    Route::post('/update/{menu}', \App\Modules\SiteMenu\Controllers\UpdateSiteMenuController::class);
    Route::delete('/delete/{menu}', \App\Modules\SiteMenu\Controllers\DeleteSiteMenuController::class);
});

