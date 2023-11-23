<?php


Route::group(['prefix' => 'v2/weather'], function () {
    Route::get('/get', \App\Modules\Weather\Controllers\GetWeatherController::class);
});

//Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'v2/role'], function () {
//    Route::get('/get-list', \App\Modules\User\Controllers\Roles\GetRoleListController::class);
//    Route::get('/get-for-user', \App\Modules\User\Controllers\Roles\GetUserRoleController::class);
//    Route::post('/change-for-user', \App\Modules\User\Controllers\Roles\ChangeUserRoleController::class);
//    Route::post('/change-permission-for-user', \App\Modules\User\Controllers\Roles\ChangeUserPermissionController::class);
//});
//
//

