<?php

namespace App\Models\Article;

use App\Models\Storage\File;
use App\Modules\File\Models\FileModel;

class ArticleModel extends \App\Modules\Article\Models\ArticleModel
{
    //


    public function files()
    {
       // return $this->hasMany('App\Modules\File\Models\FileModel', 'commentable_uid', 'uid');
        return $this->morphMany(FileModel::class, 'commentable');

    }


    public function show_resume(){
        if ($this->resume){
            return $this->resume;
        }
        $text = explode('</p>', $this->text);
        return $text[0].'</p>';
    }
    /**
     * прикрепить файлы к модели
     *
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
     * открепить все файлы от статьи
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
