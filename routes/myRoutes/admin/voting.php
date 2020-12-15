<?php



Route::resource('voting', 'Admin\AdminVotingController')
    ->only(['index', 'update', 'store', 'show']);
Route::resource('voting/questions', 'Admin\Voting\VotingQuestionController')
    ->only(['show']);
Route::resource('voting-result', 'Admin\AdminVotingResultController')
    ->only(['index', 'update', 'store', 'show']);
