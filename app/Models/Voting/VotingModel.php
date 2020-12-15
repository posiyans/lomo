<?php

namespace App\Models\Voting;

use App\Http\Resources\AnswerResource;
use App\Http\Resources\QuestionResource;
use App\Models\Stead;
use App\MyModel;
use VK\Actions\Auth;

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
     * сколько человек участввавало в голоосвании
     * берется максимальное ко-во человек ответивших на вопрос (если несколько вопросов)
     *
     * @return int
     */
    public function userAnswer(){
        $count = 0;
        foreach ($this->questions as $question) {
            if ($question->allAnswers() > $count){
                $count = $question->allAnswers();
            }
        }
        return $count;
    }

//    public function comments()
//    {
//        return $this->hasMany('App\Models\Article\CommentModel', 'article_id', 'id');
//    }

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

    public function userReturn($user_id = false)
    {
        $datetime = new \DateTime();
        $status = $this->status;
        if ($this->status == 'new') {
            if ($this->type == 'public'){
                if (strtotime($this->date_publish) < $datetime->format('U')) {
                    $status = 'execution';
                }
            } else {
                if (strtotime($this->date_start) < $datetime->format('U')) {
                    $status = 'execution';
                }
                if (strtotime($this->date_stop) < $datetime->format('U')) {
                    $status = 'done';
                }
            }
        }
        $data = [];
        if ($this->type == 'public') {
            $data = [
                'id' => $this->id,
                'title' => $this->title,
                'description' => $this->description,
                'type' => $this->type,
                'comments' => $this->comments,
                'status' => $status,
                'files' => $this->files,
                'questions' => QuestionResource::collection($this->questions),
                'created_at' => $this->updated_at,
            ];
            $questions = [];
            foreach ($this->questions as $question) {
                $answers = [];
                $myAnswer = $question->checkUserAnswer();
                foreach ($question->answers as $answer) {
                    $answers[] =  [
                        'id'=>$answer->id,
                        'text' => $answer->text,
                        'isMyAnswer' => $myAnswer ? $myAnswer->answer_id == $answer->id ? true : false : false,
                        'userAnswersCount' => count($answer->userAnswers),
                    ];
                }
                $tmp_quest = [
                    'id' => $question->id,
                    'text' => $question->text,
                    'voting_id' => $question->voting_id,
                    'answersCount' => $question->allAnswers(),
                    'answers' => $answers,
                    'myAnswers' => $myAnswer ? $myAnswer->answer_id ? $myAnswer->answer_id : false : false,
                ];
                $questions[] = $tmp_quest;
            }
            $data['questions'] = $questions;
        }
        if ($this->type == 'owner') {
            $data = [
                'id' => $this->id,
                'title' => $this->title,
                'description' => $this->description,
                'type' => $this->type,
                'comments' => $this->comments,
                'status' => $status,
                'files' => $this->files,
                'questions' => QuestionResource::collection($this->questions),
                'created_at' => $this->updated_at,
            ];
//            $questions = [];
//            foreach ($this->questions as $question) {
//                $answers = [];
//                $myAnswer = $question->checkUserAnswer();
//                foreach ($question->answers as $answer) {
//                    $answers[] =  [
//                        'id'=>$answer->id,
//                        'text' => $answer->text,
//                        'isMyAnswer' => $myAnswer ? $myAnswer->answer_id == $answer->id ? true : false : false,
//                        'userAnswersCount' => count($answer->userAnswers),
//                    ];
//                }
//                $tmp_quest = [
//                    'id' => $question->id,
//                    'text' => $question->text,
//                    'voting_id' => $question->voting_id,
//                    'answersCount' => $question->allAnswers(),
//                    'answers' => $answers,
//                    'myAnswers' => $myAnswer ? $myAnswer->answer_id ? $myAnswer->answer_id : false : false,
//                ];
//                $questions[] = $tmp_quest;
//            }
//            $data['questions'] = $questions;
        }
        return $data;

    }

}
