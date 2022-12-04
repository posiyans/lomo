<?php

namespace App\Modules\Voting\Models;

use App\Models\MyModel;
use Auth;

class UserAnswerModel extends MyModel
{

    protected $table = 'user_answer_models';
    protected $fillable= ['question_id', 'stead_id', 'user_id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (Auth::user()) {
            $this->user_id = Auth::user()->id;
        }
    }

    //
    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function stead() {
        return $this->hasOne('App\Models\Stead', 'id', 'stead_id');
    }

}
