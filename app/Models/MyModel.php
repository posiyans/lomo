<?php

namespace App\Models;

use App\Modules\Log\Models\LogModel;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MyModel
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|MyModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MyModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MyModel query()
 * @mixin \Eloquent
 */
class MyModel extends Model
{
    protected $casts = [
        'id' => 'integer'
    ];


    /**
     * под свойства хранящиеся в вполе optinons
     * @var array
     */
    protected $options_fillable = [];

    /**
     * получить массив возможных доп полей
     * @return array
     */
    public function getOptionsFillable()
    {
        return $this->options_fillable;
    }


    /**
     * Логи привязанные к модели
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function log()
    {
        return $this->morphMany('App\Models\Log', 'commentable');
    }


    public function logAndSave($description = null, array $options = [])
    {
        $original_model = $this->getOriginal();
        if ($this->save($options)) {
            LogModel::addLog($this, $original_model, $description);
            return true;
        }
        return false;
    }

    public function logAndDelete($description = null)
    {
        LogModel::deleteModel($this, $description);
        if ($this->delete()) {
            return true;
        }
        return false;
    }


}
