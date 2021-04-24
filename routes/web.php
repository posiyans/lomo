<?php
define('__ROOT__', dirname(__FILE__));
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

Route::get('/api/v1/sanctum/csrf-cookie', 'Laravel\Sanctum\Http\Controllers\CsrfCookieController@show');

Route::get('/api/v1/test', 'App\Http\Controllers\Admin\Report\PdfController@report');


Route::group(['prefix' => '/api'], function() {
    Route::get('vk/auth/callback', 'App\Http\Controllers\VkController@vkcalback');
});
Route::group(['prefix' => '/api/v1'], function() {

});

$myRoutesForAll = [
    'yandex',
    'weather',
    'auth',
    'article',
    'camera',
    'rate',
    'info',
    'receipt',
    'voting'
];
$myRoutesForAdmin = [
    'appeal',
    'article',
    'gardening',
    'user',
    'receipt',
    'billing',
    'voting',
    'setting',
    'stead',
    'owner',
    'metering_devices'
];
$myRoutesForUser = [
    'info',
    'article'
];

Route::group(['prefix' => '/api/v1'], function() use ($myRoutesForAll, $myRoutesForAdmin, $myRoutesForUser) {


    foreach ($myRoutesForAll as $route) {
        $file = __ROOT__ . '/myRoutes/all/' . $route . '.php';
        if (file_exists($file)) {
            require_once($file);
        }
    }
    Route::group(['prefix' => 'user', 'middleware' => 'auth'], function() use ($myRoutesForUser){

        foreach ($myRoutesForUser  as $route) {
            $file = __ROOT__ . '/myRoutes/user/' . $route . '.php';
            if (file_exists($file)) {
                require_once($file);
            }
        }
    });
    Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() use ($myRoutesForAdmin){

        foreach ($myRoutesForAdmin  as $route) {
            $file = __ROOT__ . '/myRoutes/admin/' . $route . '.php';
            if (file_exists($file)) {
                require_once($file);
            }
        }
    });

});


Route::resource('/api/user/storage/file', 'App\Http\Controllers\Storage\FileController');
