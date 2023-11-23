<?php

namespace App\Modules\File\Classes;

use App\Modules\File\Models\FileModel;
use App\Modules\User\Models\UserModel;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class SaveFileForObjectClass
{
    private $file_model;

    public function __construct($object)
    {
        $this->file_model = new FileModel();
        $this->file_model->commentable_type = get_class($object);
        $this->file_model->commentable_uid = $object->uid;
        // $this->file_model->commentable_id = $object->id;
    }

    public function name($name)
    {
        $this->file_model->name = $name;
        return $this;
    }

    public function size($size)
    {
        $this->file_model->size = $size;
        return $this;
    }

    public function type($type)
    {
        $this->file_model->type = $type;
        return $this;
    }

    public function uid($uid)
    {
        $this->file_model->uid = $uid;
        return $this;
    }

    public function description($text)
    {
        $this->file_model->description = $text;
        return $this;
    }

    public function file($file_path)
    {
        $hash = (new GetHashForPathClass($file_path))->run();
        $this->file_model->hash = $hash;
        $path = (new GetPathForHashClass($hash))->onlyFolder();
        Storage::putFileAs($path, new File($file_path), $this->file_model->hash);
        return $this;
    }

    /*
     * кто загрузил файл
     */
    public function uploadUser(UserModel $user)
    {
        $this->file_model->user_id = $user->id;
        return $this;
    }

    public function run()
    {
        if ($this->file_model->logAndSave('Загружен файл')) {
            return $this->file_model;
        }
        throw new \Exception('Ошибка загрузки файла');
    }
}