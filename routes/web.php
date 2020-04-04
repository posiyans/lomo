<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/', 'HomeController@index');
Auth::routes();
//Auth::routes(['verify' => true]);
Route::post('/api/register', 'Auth\RegisterController@register');
Route::post('/api/v1/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('/api/v1/password/reset', 'Auth\ResetPasswordController@reset');
Route::post('/api/v1/login', 'Auth\LoginController@login');

Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');


Route::get('/vk', 'HomeController@vk')->name('vk');
Route::get('/vk/auth/callback', 'VkController@vkcalback');
Route::get('/home', 'HomeController@index')->name('home');
Route::match(['get', 'post'], '/ticket/{id}', 'ReceiptController@ticket')->name('ticket');
Route::get('/qrcode/ticket/{id}/{fio}', 'QrCodeController@getImage');
//Route::get('/user/get-receipt-clear', 'PdfController@clearReceipt');
Route::get('/user/receipt/get-receipt-clear', 'PdfController@clearReceipt');
Route::get('/user/receipt/get-qrcode-clear', 'QrCodeController@qrCodeClear');
Route::get('/pdf/ticket/{id}/{stead}', 'PdfController@renderPdf')->name('renderPdf');
Route::match(['get', 'post'], '/receipt/{id?}', 'ReceiptController@index')->name('receipt');
Route::match(['get', 'post'], '/report', 'ReceiptController@index')->name('report');
Route::get('/personal', function () {
    return view('personal');
})->name('personal');
//Route::get('/vue', function () {
//    return view('vue');
//})->name('vue');

Route::get('/rasp', 'RaspController@index');
Route::get('/temper', 'TemperController@index');
Route::get('/temper/get', 'TemperController@showGrafTemper');
Route::get('/api/temper/get', 'TemperController@showGrafTemper');
Route::post('/temper/now', 'TemperController@showLocalTemper'); // old
Route::post('/api/temper/now', 'TemperController@showLocalTemper');
Route::get('/api/v1/user', 'UserController@info');
Route::post('/api/v1/login', 'Auth\LoginController@apiLogin');
Route::post('/api/v1/login-vk', 'VkController@getUrl');
Route::post('/api/v1/user-logout', 'Auth\LoginController@logout');

Route::resource('/api/v1/user/category', 'User\CategoryController')
    ->only(['index']);
Route::resource('/api/v1/user/article', 'User\ArticleController')
    ->only(['index', 'show']);
Route::resource('/api/v1/user/gardening', 'User\GardeningController')
    ->only(['index', 'show']);

Route::group(['middleware' => 'auth'], function () {

    Route::get('/api/v1/admin/user-info', 'UserController@info');
    Route::post('/api/v1/user/save-user-info', 'UserController@save');
    Route::get('/api/v1/user/steads-list', 'SteadController@list');
    Route::get('/profile', 'UserController@index');

    Route::get('/api/v1/user/info', 'UserController@info');

    // обращения
    Route::get('/api/v1/admin/appeal/info', 'UserController@info');
    Route::resource('/api/v1/admin/appeal', 'Admin\AppealController')
        ->only(['index', 'show', 'update']);
    Route::resource('/api/v1/admin/stead', 'Admin\SteadController')
        ->only(['index', 'show']);
    Route::resource('/api/v1/admin/user', 'Admin\UserController')
        ->only(['index', 'show', 'update']);
    Route::resource('/api/v1/admin/role', 'Admin\RoleController')
        ->only(['index', 'update']);
    Route::resource('/api/v1/admin/rate', 'Admin\RateController')
    ->only(['index', 'update']);
    Route::resource('/api/v1/admin/category', 'Admin\CategoryController')
        ->only(['index', 'update', 'store']);
    Route::resource('/api/v1/admin/article', 'Admin\ArticleController')
        ->only(['index', 'update', 'store', 'show']);
    Route::get('/api/v1/admin/role-list', 'Admin\UserController@roleList');
    Route::resource('/api/v1/admin/voting', 'Admin\AdminVotingController')
        ->only(['index', 'update', 'store', 'show']);
    Route::resource('/api/v1/admin/voting-result', 'Admin\AdminVotingResultController')
        ->only(['index', 'update', 'store', 'show']);
//        ->only(['index', 'update', 'store', 'show']);


});
Route::resource('/user/storage/file', 'Storage\FileController');
Route::resource('/api/user/storage/file', 'Storage\FileController');
