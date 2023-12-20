<?php

namespace App\Modules\File\AccessClass;

use App\Modules\BanUser\Actions\CheckUserBanAction;
use App\Modules\File\Repositories\GetObjectForFileRepository;

/**
 * Проверить на право на фаил
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
        if ($model->public == 2) {
            return true;
        }
        if (!$this->user) {
            throw new \Exception('Ошибка доступа');
        }
        if ($this->file->user_id == $this->user->id) {
            return true;
        }
        if ($this->user->ability('superAdmin', 'article-edit', 'article-show')) {
            return true;
        }
        if ($model->public == 5 && $this->user->hasRole('owners')) {
            return true;
        }
        throw new \Exception('Ошибка доступа');
    }

    public function write()
    {
        (new CheckUserBanAction($this->user))
            ->type('article')
            ->run();
    }

    public function delete()
    {
        throw new \Exception('Ошибка доступа');
    }

}