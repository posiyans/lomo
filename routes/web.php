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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
//Route::get('/vk', 'HomeController@vk')->name('vk');
//Route::get('/callback', 'HomeController@vkcalback');
Route::get('/home', 'HomeController@index')->name('home');
Route::match(['get', 'post'], '/ticket/{id}', 'ReceiptController@ticket')->name('ticket');
Route::get('/qrcode/ticket/{id}/{fio}', 'QrCodeController@getImage');
Route::get('/pdf/ticket/{id}/{stead}', 'PdfController@renderPdf')->name('renderPdf');
Route::match(['get', 'post'], '/receipt/{id?}', 'ReceiptController@index')->name('receipt');
Route::match(['get', 'post'], '/report', 'ReceiptController@index')->name('report');
Route::get('/personal', function () {
    return view('personal');
})->name('personal');;
