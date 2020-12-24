<?php



Route::resource('voting/questions', 'Admin\Voting\VotingQuestionController')
    ->only(['show']);
Route::resource('voting/result', 'Admin\Voting\AdminVotingResultController')
    ->only(['index', 'update', 'store', 'show']);
Route::resource('voting', 'Admin\AdminVotingController')
    ->only(['index', 'update', 'store', 'show']);
Route::post('voting/owner/add-answer', 'Admin\Voting\AdminVotingUserAnswerController@addUserAnswer');
Route::get('voting/owner/get-file', 'Admin\Voting\AdminVotingFileController@getFile');
Route::post('voting/owner/get-bulletin-list', 'Admin\Voting\AdminVotingFileController@getBulletinList');
Route::post('voting/owner/upload-file', 'Admin\Voting\AdminVotingFileController@uploadFile');
