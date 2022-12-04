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
Route::group(['prefix' => '/api/v2/voting'], function() {
    Route::get('/result/question', [\App\Modules\Voting\Controllers\GetVotingResultQuestionController::class, 'index']);
    Route::get('/question/get-for-voting', [\App\Modules\Voting\Controllers\GetQuestionsForVotingController::class, 'index']);
    Route::get('/answer/get-for-question', [\App\Modules\Voting\Controllers\GetAnswersForQuestionController::class, 'index']);
});