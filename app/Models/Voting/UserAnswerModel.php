<?php

namespace App\Models\Voting;

use App\MyModel;

class UserAnswerModel extends MyModel
{

    protected $table = 'user_answer_models';
    protected $fillable= ['question_id', 'stead_id', 'user_id'];
    //
    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function stead() {
        return $this->hasOne('App\Models\Stead', 'id', 'stead_id');
    }

}
