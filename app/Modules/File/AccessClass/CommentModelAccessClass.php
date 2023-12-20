<?php

namespace App\Modules\File\AccessClass;

use App\Modules\File\Repositories\GetObjectForFileRepository;

/**
 * Проверить на право на фаил
 *
 */
class CommentModelAccessClass extends FileAccessAbstract
{
    private $file;
    private $user;

    public function __construct($opt = [])
    {
        $this->file = $opt[0]; // обьект при write и сам файл при чтении
        $this->user = $opt[1]; // пользователь кто запрашивает фаил
    }

    public function read()
    {
        $model = (new GetObjectForFileRepository($this->file))->run();
        if ($model->user_id == $this->user->id) {
            return true;
        }
        if ($this->file->user_id == $this->user->id) {
            return true;
        }
//        if ($this->user->ability('superAdmin', 'appeal-edit', 'appeal-show')) {
//            return true;
//        }
        throw new \Exception('Ошибка доступа');
    }

    public function write()
    {
        $model = get_class($this->file)::where('uid', $this->file->uid)->first();
        if (!$this->user) {
            throw new \Exception('Ошибка доступа1');
        }
        if ($model->user_id == $this->user->id) {
            return true;
        }
        throw new \Exception('Ошибка доступа');
    }

    public function delete()
    {
        throw new \Exception('Ошибка доступа');
    }

}