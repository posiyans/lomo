<?php



Route::resource('voting/questions', 'App\Http\Controllers\Admin\Voting\VotingQuestionController')
    ->only(['show']);
Route::resource('voting/result', 'App\Http\Controllers\Admin\Voting\AdminVotingResultController')
    ->only(['index', 'update', 'store', 'show']);
Route::resource('voting', 'App\Http\Controllers\Admin\AdminVotingController')
    ->only(['index', 'update', 'store', 'show']);
Route::post('voting/owner/add-answer', 'App\Http\Controllers\Admin\Voting\AdminVotingUserAnswerController@addUserAnswer');
Route::get('voting/owner/get-file', 'App\Http\Controllers\Admin\Voting\AdminVotingFileController@getFile');
Route::post('voting/owner/get-bulletin-list', 'App\Http\Controllers\Admin\Voting\AdminVotingFileController@getBulletinList');
Route::post('voting/owner/upload-file', 'App\Http\Controllers\Admin\Voting\AdminVotingFileController@uploadFile');
