<?php

namespace App\Models;

use App\MyModel;
Use Illuminate\Support\Facades\Auth;

/**
 * Модель логов
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
        $this ->action = debug_backtrace()[1]['function'];
        $this ->user_agent = $_SERVER['REMOTE_ADDR'].'/'.$_SERVER['HTTP_USER_AGENT'];
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
     * @param $model_new -- модель после сохранния
     * @param $model_old -- массив оригинальных атрибутов полученных перед сохранением через getOriginal();
     * @param null $description -- описание
     * @param null $stead_id -- id участка к которому как-то относится данное действие
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
                if (is_array($model_new->$attribute) && $model_old[$attribute] != json_encode($model_new->$attribute)) {
                 //   $new = false;
                }
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
            $log->description  = $description;
            $log->stead_id = $stead_id;
            $log->value = $diff;
            if ($log->save())
            {
                return $log;
            }
        }
        return false;
    }
}
