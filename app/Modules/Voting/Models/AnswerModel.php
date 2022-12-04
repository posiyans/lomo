<?php

namespace App\Modules\Voting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnswerModel extends Model
{
    use SoftDeletes;

    /**
     * получить вопрос
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(QuestionModel::class, 'question_id', 'id');
    }


    /**
     * получить кто дал этот ответ
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userAnswers()
    {
        return $this->hasMany('App\Models\Voting\UserAnswerModel', 'answer_id', 'id');
    }

    /**
     *получить голосование
     */
    public function voting(){
        return $this->question->voting;
    }


//    /**
//     * пользователь выбрал ответ
//     *
//     * @param $user_id
//     * @param $answer_id
//     */
//    public static function userVoting($user_id, $answer_id)
//    {
//
//        if ($answer = AnswerModel::find($answer_id)){
//            $voting = $answer->voting();
//            if ($voting->type == 'public' && !$answer->question->checkUserAnswer($user_id)){
//                $rez = new UserAnswerModel();
//                $rez->question_id = $answer->question->id;
//                $rez->answer_id = $answer->id;
//                $rez->user_id = $user_id;
//                $rez->user_id = $user_id;
//                $rez->stead_id = null;
//               if ($rez->save()){
//                   return $rez;
//               }
//            }
//        }
//        return false;
//    }

//    public function getUserAnswers()
//    {
//        $type = $this->question->voting->type;
//        $userAnswers = $this->userAnswers;
//        $data = [];
//        foreach ($userAnswers as $item) {
//            if ($type == 'public') {
//                $temp = [
//                    'created_at' =>$item->creted_at,
//                    'user_id' => $item->user_id,
//                    'id' => 'user_id_'.$item->user_id,
//                    'user_name' => $item->user->last_name . ' ' . $item->user->name . ' ' . $item->user->moddle_name
//                ];
//                $data['user_id_'.$item->user_id] = $temp;
//            }
//        }
//        return $data;
//    }
}
