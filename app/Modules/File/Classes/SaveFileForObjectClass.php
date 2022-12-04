<?php
namespace App\Modules\File\Classes;

use App\Modules\File\Models\FileModel;
use Ramsey\Uuid\Nonstandard\Uuid;

class SaveFileForObjectClass
{
    private $file_model;

    public function __construct($object)
    {
        $this->file_model = new FileModel();
        $this->file_model->commentable_type = get_class($object);
        $this->file_model->commentable_uid = $object->uid;
        $this->file_model->commentable_id = $object->id;
        $this->file_model->uid = Uuid::uuid4();
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
    public function file($file_path): SaveFileForObjectClass
    {
        $hash = (new GetHashForPathClass($file_path))->run();
        $this->file_model->hash = $hash;
        $new_path = (new GetPathForHashClass($hash))->run();
        if (!file_exists($new_path)) {
            rename($file_path, $new_path);
        }
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