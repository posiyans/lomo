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

//Route::get('/', function () {
//    return view('welcome');
//});
////Route::get('/', 'HomeController@index');
Route::get('/api/v1/test', 'Admin\Report\PdfController@report');
//Route::get('/api/v1/test_s', 'TestController@index');
//Auth::routes();

Route::group(['prefix' => '/api'], function() {
    // маршруты для callback контакта
//    Route::get('vk', 'HomeController@vk')->name('vk');
    Route::get('vk/auth/callback', 'VkController@vkcalback');
});
Route::group(['prefix' => '/api/v1'], function() {
//    //маршруты авторизации и регистрации
//    Route::post('/register', 'Auth\RegisterController@register');
//    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
//    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
//    Route::post('login', 'Auth\LoginController@login');
//    Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('api.verification.verify');

    // пустая квитанция
//    Route::get('receipt/get-receipt-clear', 'PdfController@clearReceipt');
    // пустой QR code
//    Route::get('receipt/get-qrcode-clear', 'QrCodeController@qrCodeClear');

//    // получить картинку с камеры (для cron)
//    Route::get('camera', 'Camera\CameraController@index');
//    // вывести последнюю картинку
//    Route::get('camera/img/{id?}', 'Camera\CameraController@getImages');
//    //    склесть gif из картинок с камеры (на будующее)
//    Route::get('/api/v1/camera/create-gif/{token?}', 'Camera\CameraController@createGif');


});


//Route::match(['get', 'post'], '/ticket/{id}', 'ReceiptController@ticket')->name('ticket');
//Route::get('/qrcode/ticket/{id}/{fio}', 'QrCodeController@getImage');

//Route::get('/pdf/ticket/{id}/{stead}', 'PdfController@renderPdf')->name('renderPdf');
//Route::match(['get', 'post'], '/receipt/{id?}', 'ReceiptController@index')->name('receipt');
//Route::match(['get', 'post'], '/report', 'ReceiptController@index')->name('report');
//Route::get('/personal', function () {
//    return view('personal');
//})->name('personal');

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



//Route::resource('/api/v1/user/gardening', 'User\GardeningController')
//    ->only(['index', 'show']);

//Route::resource('/api/v1/user/rate', 'User\RateController')
//    ->only(['index']);


Route::resource('/api/user/storage/file', 'Storage\FileController');
