<?php
//define('__ROOT__', dirname(__FILE__));
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['prefix' => '/api/v2/stead'], function() {
    Route::get('/list', [\App\Modules\Stead\Controllers\GetSteadListController::class, 'index']);
    Route::get('/info', [\App\Modules\Stead\Controllers\GetSteadInfoController::class, 'index']);
});