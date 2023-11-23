<?php

namespace App\Models\Voting;

use App\Models\MyModel;
use Auth;

/**
 * App\Models\Voting\UserAnswerModel
 *
 * @property int $id
 * @property int $question_id id вопроса
 * @property int $answer_id id ответа
 * @property int $user_id id пользователя
 * @property int|null $stead_id id участка
 * @property int|null $power_of_attorney_id id доверенности
 * @property int $not_valid недествительный
 * @property string|null $description описание
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @property-read \App\Models\Stead|null $stead
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswerModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswerModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswerModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswerModel whereAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswerModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswerModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswerModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswerModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswerModel whereNotValid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswerModel wherePowerOfAttorneyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswerModel whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswerModel whereSteadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswerModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswerModel whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @mixin \Eloquent
 */
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
