<?php
namespace App\Modules\File\Classes;

class GetHashForPathClass
{
    public $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function run()
    {
        if (file_exists($this->path)) {
            $md5 = md5_file($this->path);
            return $md5;
        }
        throw new \Exception('Файл не найден');
    }
}