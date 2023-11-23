<?php

namespace App\Modules\Log\Models;

use App\Models\MyModel;
use Illuminate\Support\Facades\Auth;

/**
 * Модель логов
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $stead_id
 * @property int|null $commentable_id
 * @property string|null $commentable_type
 * @property string|null $type
 * @property string|null $action
 * @property string|null $user_agent
 * @property string|null $description
 * @property array|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereSteadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereValue($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @mixin \Eloquent
 */
class Log extends MyModel
{
    protected $casts = [
        'value' => 'array',
    ];

    /**
     * Log constructor.
     */
    public function __construct()
    {
        if (Auth::check()) {
            $this->user_id = Auth::user()->id;
        }
        $this->action = debug_backtrace()[1]['function'];
        $this->user_agent = '';
        $this->user_agent .= isset($_SERVER['REMOTE_ADDR']) ? '_' . $_SERVER['REMOTE_ADDR'] : '';
        $this->user_agent .= isset($_SERVER['HTTP_USER_AGENT']) ? '_' . $_SERVER['HTTP_USER_AGENT'] : '';
    }

    /**
     * Получить все модели, обладающие commentable.
     */
    public function commentable()
    {
        return $this->morphTo();
    }

//    /**
//     * сравниваем 2 обьекта и разницу сохраняем в лог
//     *
//     * @param $objNew new object
//     * @param $objOld old object
//     * @param bool $description описание
//     */
//    public static function saveDiff($objNew, $objOld, $description=false){
//            $log = new Log();
//            if ($diff = $log->diff($objNew, $objOld)) {
//                $log->type = 'ok';
//                if ($description) {
//                    $log->description = $description;
//                } else {
//                    $log->description = 'change to ' . get_class($objOld);
//                }
//                $log->value = $diff;
//                $objOld->log()->save($log);
//            }
//    }
//
//    public function diff($objNew, $objOld)
//    {
//        $diff = [];
//        if (is_object($objNew)) {
//            $objNew = $objNew->toArray();
//        }
//        if (is_object($objOld)) {
//            $objOld = $objOld->toArray();
//        }
//        foreach ($objNew as $key => $value){
//            if (!is_array($value)){
//                if ($objOld[$key] != $objNew[$key]) {
//                    $diff[$key] = ['old'=>$objOld[$key], 'new'=>$objNew[$key]];
//                }
//            } else {
//                if (isset($objOld[$key])) {
//                    $val = $this->diff($objNew[$key], $objOld[$key]);
//                    if ($val and count($val) > 0) {
//                        $diff[$key] = $val;
//                    }
//                }else {
//                    $diff[$key] = ['old' => '', 'new' => $objNew[$key]];
//                }
//            }
//        }
//        if (count($diff) > 0){
//            return $diff;
//        }
//        return false;
//    }

    /**
     * добавьть лог с разницей состояния моделей
     *
     * @param $model_new  -- модель после сохранния
     * @param $model_old  -- массив оригинальных атрибутов полученных перед сохранением через getOriginal();
     * @param  null  $description  -- описание
     * @param  null  $stead_id  -- id участка к которому как-то относится данное действие
     * @return bool
     */
    public static function addLog($model_new, $model_old, $description = null, $stead_id = null)
    {
        $attributes = $model_new->getAttributes();
        if (isset($attributes['updated_at'])) {
            unset($attributes['updated_at']);
        }
        $diff = [];
        foreach (array_keys($attributes) as $attribute) {
            if (!isset($model_old[$attribute]) || $model_old[$attribute] != $model_new->$attribute) {
                $new = true;
                // todo не работает с json объектами!!!!
//                if (is_array($model_new->$attribute) && $model_old[$attribute] != json_encode($model_new->$attribute)) {
//                 //   $new = false;
//                }
                if ($model_new->$attribute == null && (!isset($model_old[$attribute]) || $model_old[$attribute] == '')) {
                    $new = false;
                }
                if ($new) {
                    $diff[] = [
                        $attribute => [
                            'new' => $model_new->$attribute,
                            'old' => isset($model_old[$attribute]) ? $model_old[$attribute] : ''
                        ]
                    ];
                }
            }
        }
        if (count($diff) > 0) {
            $log = new self();
            $log->commentable_id = $model_new->id;
            $log->commentable_type = get_class($model_new);
            $log->type = 'ok';
            $log->description = $description;
            $log->stead_id = $stead_id;
            $log->value = $diff;
            if ($log->save()) {
                return $log;
            }
        }
        return false;
    }
}