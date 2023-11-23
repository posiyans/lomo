<?php

namespace App\Models\Voting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Voting\AnswerModel
 *
 * @property int $id
 * @property int $question_id
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Voting\QuestionModel|null $question
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Voting\UserAnswerModel> $userAnswers
 * @property-read int|null $user_answers_count
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerModel whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerModel whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerModel withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Voting\UserAnswerModel> $userAnswers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Voting\UserAnswerModel> $userAnswers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Voting\UserAnswerModel> $userAnswers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Voting\UserAnswerModel> $userAnswers
 * @mixin \Eloquent
 */
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
        return $this->belongsTo('App\Models\Voting\QuestionModel', 'question_id', 'id');
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


    /**
     * пользователь выбрал ответ
     *
     * @param $user_id
     * @param $answer_id
     */
    public static function userVoting($user_id, $answer_id)
    {

        if ($answer = AnswerModel::find($answer_id)){
            $voting = $answer->voting();
            if ($voting->type == 'public' && !$answer->question->checkUserAnswer($user_id)){
                $rez = new UserAnswerModel();
                $rez->question_id = $answer->question->id;
                $rez->answer_id = $answer->id;
                $rez->user_id = $user_id;
                $rez->user_id = $user_id;
                $rez->stead_id = null;
               if ($rez->save()){
                   return $rez;
               }
            }
        }
        return false;
    }

    public function getUserAnswers()
    {
        $type = $this->question->voting->type;
        $userAnswers = $this->userAnswers;
        $data = [];
        foreach ($userAnswers as $item) {
            if ($type == 'public') {
                $temp = [
                    'created_at' =>$item->creted_at,
                    'user_id' => $item->user_id,
                    'id' => 'user_id_'.$item->user_id,
                    'user_name' => $item->user->last_name . ' ' . $item->user->name . ' ' . $item->user->moddle_name
                ];
                $data['user_id_'.$item->user_id] = $temp;
            }
        }
        return $data;
    }
}
