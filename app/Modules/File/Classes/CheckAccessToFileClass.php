<?php

namespace App\Modules\File\Classes;

use App\Modules\Article\Models\ArticleModel;
use App\Modules\File\AccessClass\ArticleModelAccessClass;
use App\Modules\File\Models\FileModel;
use Illuminate\Support\Facades\Auth;

class CheckAccessToFileClass
{
    private $file;
    private $user;
    private $classMap = [
        ArticleModel::class => ArticleModelAccessClass::class,
    ];


    public function __construct()
    {
        $this->user = Auth::user() ? Auth::user() : null;
    }

    public function file(FileModel $file): CheckAccessToFileClass
    {
        $this->file = $file;
        return $this;
    }

    public function forUser($user): CheckAccessToFileClass
    {
        $this->user = $user;
        return $this;
    }

    public function read()
    {
        $class = $this->file->commentable_type;
        if (isset($this->classMap[$class])) {
            return (new $this->classMap[$class]([$this->file, $this->user]))->read();
        }
        throw new \Exception('Ошибка определения прав на файл');
    }


    public function write($class)
    {
        if (isset($this->classMap[$class])) {
            return (new $this->classMap[$class]([null, $this->user]))->write();
        }
        throw new \Exception('Ошибка определения прав на файл');
    }

}