<?php

namespace App\Modules\File\AccessClass;

use App\Modules\File\Repositories\GetObjectForFileRepository;

/**
 * Проверить на право удалить фаил
 *
 */
class ArticleModelAccessClass extends FileAccessAbstract
{
    private $file;
    private $user;

    public function __construct($opt = [])
    {
        $this->file = $opt[0];
        $this->user = $opt[1];
    }

    public function read()
    {
        $model = (new GetObjectForFileRepository($this->file))->run();
        if ($model->public == 1) {
            return true;
        }
        if (!$this->user) {
            return false;
        }
        if ($this->file->user_id == $this->user->id) {
            return true;
        }
        if ($this->user->ability('superAdmin', 'article-edit', 'article-show')) {
            return true;
        }
        if ($model->public == 4 && $this->user->hasRole('owners')) {
            return true;
        }
        return false;
    }

    public function write()
    {
        return false;
    }
}