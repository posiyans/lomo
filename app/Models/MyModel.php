<?php

namespace App\Models;

use App\Models\Log;
use App\Models\Storage\File;
use Illuminate\Database\Eloquent\Model;

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


    /**
     * @deprecated
     *
     * Файлы привязанные к модели
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function files()
    {
     return $this->morphMany('App\Models\Storage\File', 'commentable');
    }

    /**
     * @deprecated
     *
     * Комментарии привязанные к модели
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function message()
    {
        return $this->morphMany('App\Models\Message\MessageModel', 'commentable')->with('user');
    }

    /**
     *
     * @deprecated
     *
     * прикрепить файлы к модели
     * @param $files
     */
    public function attachedFiles($files)
    {
        if (is_array($files)) {
            if ($this->deattachedAllFiles()) {
                foreach ($files as $file) {
                    $f = false;
//                    if (isset($file['response']['files']['id'])) {
//                        $f = File::find($file['response']['files']['id']);
//                    } else
                    if (isset($file['id'])){
                        $f = File::find($file['id']);
                    }
                    if ($f) {
                        $f->commentable_type = get_class($this);
                        $f->commentable_id = $this->id;
                        $f->save();
                    }
                }
            }
        }
    }

    /**
     *
     * @deprecated
     *
     * открепить все файлы от модели
     *
     * @return bool
     */
    public function deattachedAllFiles()
    {
        $files = $this->files;
        foreach ($files as $file) {
            $file->commentable_id = null;
            $file->save();
        }
        return true;
    }
}
