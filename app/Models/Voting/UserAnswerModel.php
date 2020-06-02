<?php

namespace App\Models\Voting;

use Illuminate\Database\Eloquent\Model;

class UserAnswerModel extends Model
{
    //
    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
