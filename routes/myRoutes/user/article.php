<?php

Route::resource('comment', 'User\CommentController')
    ->only(['store']);
