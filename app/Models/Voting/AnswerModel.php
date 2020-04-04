<?php

namespace App\Models\Voting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnswerModel extends Model
{
    use SoftDeletes;

    public function question()
    {
        return $this->belongsTo('App\Models\Voting\QuestionModel', 'id', 'question_id');
    }

    public function userAnswers()
    {
        return $this->hasMany('App\Models\Voting\UserAnswerModel', 'answer_id', 'id');
    }

}
