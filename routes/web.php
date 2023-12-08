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

Auth::routes();
Route::get('/', function (\Illuminate\Http\Request $request) {
    $user_uid = $request->session()->get('user_uid');
    return response(['user_uid' => $user_uid, 'auth' => Auth::user()]);
})->name('home');
Route::get('/api/v1/test', App\Http\Controllers\Admin\Report\PdfController::class);

$myRoutesForAll = [
    'weather',
    'auth',
    'camera',
    'rate',
    'info',
    'receipt',
    'voting'
];
$myRoutesForAdmin = [
    'appeal',
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

Route::group(['prefix' => '/api/v1'], function () use ($myRoutesForAll, $myRoutesForAdmin) {
    foreach ($myRoutesForAll as $route) {
        $file = __ROOT__ . '/myRoutes/all/' . $route . '.php';
        if (file_exists($file)) {
            require_once($file);
        }
    }
    Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () use ($myRoutesForAdmin) {
        foreach ($myRoutesForAdmin as $route) {
            $file = __ROOT__ . '/myRoutes/admin/' . $route . '.php';
            if (file_exists($file)) {
                require_once($file);
            }
        }
    });
});


Route::resource('/api/user/storage/file', 'App\Http\Controllers\Storage\FileController');
