<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v2/owner'], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('compare-owner-and-user', \App\Modules\Owner\Controllers\CompareOwnerAndUserController::class);
        Route::post('delete-user-from-owner', \App\Modules\Owner\Controllers\DeleteUserFromOwnerController::class);
        Route::post('create', \App\Modules\Owner\Controllers\CreateOwnerUserController::class);
        Route::get('get-field-list', \App\Modules\Owner\Controllers\GetOwnerFieldListController::class);
        Route::get('get-list', \App\Modules\Owner\Controllers\GetOwnerUserListController::class);
        Route::get('get-list-xlsx', \App\Modules\Owner\Controllers\GetOwnerUserListInXlsxController::class);
        Route::get('get/{owner}', \App\Modules\Owner\Controllers\GetOwnerUserInfoController::class);
        Route::delete('delete/{owner}', \App\Modules\Owner\Controllers\DeleteOwnerUserController::class);
        Route::post('update/{owner}', \App\Modules\Owner\Controllers\UpdateOwnerUserFieldController::class);
        Route::post('add-stead', \App\Modules\Owner\Controllers\AddSteadToOwnerUserController::class);
    });
//    Route::get('get-qrcode', \App\Modules\Gardening\Controllers\GetGardeningInfoController::class);
});


