<?php

namespace App\Modules\Voting\Models;

use App\Models\MyModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class QuestionModel extends MyModel
{
    use SoftDeletes;
//    protected $fillable= [];

    public function answers()
    {
        return $this->hasMany(AnswerModel::class, 'question_id');
    }

    public function voting()
    {
        return $this->belongsTo('App\Models\Voting\VotingModel', 'voting_id', 'id');
    }

//    public function allAnswers(){
//        $count = 0;
//        foreach ($this->answers as $answer) {
//            $count += count($answer->userAnswers);
//        }
//        return $count;
//    }
//    /**
//     * сохранить ответы для вопросов голосования
//     *
//     * @param $answers
//     */
//    public function saveAnswers($answers){
//        if (is_array($answers)) {
//            foreach ($answers as $answer) {
//                if (isset($answer['id'])){
//                    $answerModel  = AnswerModel::find($answer['id']);
//                } else {
//                    $answerModel = new AnswerModel();
//                    $answerModel->question_id = $this->id;
//                }
//                if ($answer['text'] ==''){
//                    $answerModel->delete();
//                } else {
//                    $answerModel->text = $answer['text'];
//                    $answerModel->save();
//                }
//
//            }
//        }
//    }

//    public function checkUserAnswer($user_id = false)
//    {
//        if (!$user_id) {
//            if ($user= Auth::user()){
//                $user_id = $user->id;
//            }
//        }
//        if ($this->voting->type == 'public') {
//            return UserAnswerModel::where('user_id', $user_id)->where('question_id', $this->id)->first();
//        }
//        if ($this->voting->type == 'owner') {
//            return 'owner';
//        }
//        return true;
//    }

//
//    /**
//     * проверить принадлежит ли ответ данному вопросу
//     *
//     * @param $id
//     * @return bool
//     */
//    public function checkAnswerId($id)
//    {
//        return (boolean)AnswerModel::where('question_id', $this->id)->first();
//    }

//    /**
//     * добавить ответ участка по голосованию по данному вопросу
//     *
//     * @param $answer
//     * @param $stead_id
//     * @param $user_id
//     * @return false
//     */
//    public function setOwnerAnswer($answer, $stead_id)
//    {
//        if ($this->checkAnswerId($answer)) {
//            $result = UserAnswerModel::firstOrNew(['question_id' => $this->id, 'stead_id' => $stead_id]);
//            if ($result && $result->answer_id != $answer) {
//                $result->answer_id = $answer;
//                if ($result->id) {
//                    $text = 'Измение';
//                } else {
//                    $text = 'Добавление';
//                }
//                if ($result->logAndSave($text, $stead_id)) {
//                    return $result;
//                }
//            }
//        }
//        return false;
//    }

//    public function deleteOwnerAnswer($stead_id, $user_id)
//    {
//        $result = UserAnswerModel::where(['question_id' => $this->id, 'stead_id' => $stead_id])->first();
//        if ($result) {
//                $result->delete();
//        }
//    }

}
