<?php

namespace App\Models\Voting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionModel extends Model
{
    use SoftDeletes;

    public function answers()
    {
        return $this->hasMany('App\Models\Voting\AnswerModel', 'question_id');
    }

    public function voting()
    {
        return $this->belongsTo('App\Models\Voting\VotingModel', 'id', 'voting_id');
    }

    public function allAnswers(){
        $count = 0;
        foreach ($this->answers as $answer) {
            $count += count($answer->userAnswers);
        }
        return $count;
    }
    /**
     * сохранить ответы для вопросов голосования
     *
     * @param $answers
     */
    public function saveAnswers($answers){
        if (is_array($answers)) {
            foreach ($answers as $answer) {
                if (isset($answer['id'])){
                    $answerModel  = AnswerModel::find($answer['id']);
                } else {
                    $answerModel = new AnswerModel();
                    $answerModel->question_id = $this->id;
                }
                if ($answer['text'] ==''){
                    $answerModel->delete();
                } else {
                    $answerModel->text = $answer['text'];
                    $answerModel->save();
                }

            }
        }
    }
}
