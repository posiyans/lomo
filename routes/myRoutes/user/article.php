<?php

Route::resource('comment', 'App\Http\Controllers\User\CommentController')
    ->only(['store']);
