<?php

namespace App\Models\Voting;

use App\MyModel;

class VotingModel extends MyModel
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'answer' => 'array',
    ];

    protected $fillable = [
        'title',
        'date_publish',
        'date_start',
        'date_stop',
        'description',
        'type',
        'comments',
        'status',
        'answer',
        'public',
    ];
    protected $guarded = ['id'];
//    protected $fillable = [ 'title' ];

    public function questions()
    {
        return $this->hasMany('App\Models\Voting\QuestionModel', 'voting_id');
    }


    /**
     * сохранить вопросы для голосования
     *
     * @param $questions
     */
    public function saveQuestions($questions){
        if (is_array($questions)) {
            foreach ($questions as $question) {
                if (isset($question['id'])){
                    $questionModel  = QuestionModel::find($question['id']);
                } else {
                    $questionModel = new QuestionModel();
                    $questionModel->voting_id = $this->id;
                }
                if ($question['text'] == '' ){
                    $questionModel->delete();
                } else {
                    $questionModel->text = $question['text'];
                    if ($questionModel->save()) {
                        if (isset($question['answers'])) {
                            $questionModel->saveAnswers($question['answers']);
                        }
                    }
                }
            }
        }
    }

}
