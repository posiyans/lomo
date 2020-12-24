<?php



Route::resource('voting/questions', 'Admin\Voting\VotingQuestionController')
    ->only(['show']);
Route::resource('voting/result', 'Admin\Voting\AdminVotingResultController')
    ->only(['index', 'update', 'store', 'show']);
Route::resource('voting', 'Admin\AdminVotingController')
    ->only(['index', 'update', 'store', 'show']);
Route::post('voting/owner/add-answer', 'Admin\Voting\AdminVotingUserAnswerController@addUserAnswer');
