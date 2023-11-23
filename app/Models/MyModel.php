<?php

namespace App\Models;

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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @mixin \Eloquent
 */
class MyModel extends Model
{

    /**
     * Логи привязанные к модели
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function log()
    {
        return $this->morphMany('App\Models\Log', 'commentable');
    }


    public function logAndSave($description = null, $stead = null, array $options = [])
    {
        $original_model = $this->getOriginal();
        if ($this->save($options)) {
            Log::addLog($this, $original_model, $description, $stead);
            return true;
        }
        return false;
    }

    public function logAndDelete($description = null, $stead = null)
    {
        $original_model = $this->getOriginal();
        Log::deleteModel($this, $description, $stead);
        if ($this->delete()) {
            return true;
        }
        return false;
    }


}
