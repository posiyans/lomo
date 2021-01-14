<?php

Route::resource('gardening', 'User\GardeningController')
    ->only(['store']); //todo нге работает заглушка
