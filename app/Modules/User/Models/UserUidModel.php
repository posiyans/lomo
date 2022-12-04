<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class UserUidModel extends \App\Models\MyModel
{
    use HasFactory;
//    protected $casts = [
//        'user_id' => 'encrypted',
//    ];

    public function user() {
        return $this->hasOne('App\Modules\User\Models\UserModel', 'id', 'user_id');
    }
}
