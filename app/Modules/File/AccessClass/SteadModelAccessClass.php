<?php

namespace App\Modules\File\AccessClass;

/**
 * Проверить на право на фаил
 *
 */
class SteadModelAccessClass extends FileAccessAbstract
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
        if ($this->user->ability('superAdmin', 'stead-edit', 'stead-show')) {
            return true;
        }
        throw new \Exception('Ошибка доступа');
    }

    public function write()
    {
//        $model = get_class($this->file)::where('uid', $this->file->uid)->first();
//        if (!$this->user) {
//            throw new \Exception('Ошибка доступа');
//        }
//        if ($model->user_id == $this->user->id) {
//            return true;
//        }
        if ($this->user->ability('superAdmin', 'stead-edit', 'stead-show')) {
            return true;
        }
        throw new \Exception('Ошибка доступа');
    }

    public function delete()
    {
        if ($this->user->roles('superAdmin')) {
            return true;
        }
        throw new \Exception('Ошибка доступа');
    }

}