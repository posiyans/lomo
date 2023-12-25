<?php

namespace App\Modules\File\Classes;

use App\Modules\Appeal\Modules\AppealModel;
use App\Modules\Article\Models\ArticleModel;
use App\Modules\Comment\Models\CommentModel;
use App\Modules\File\AccessClass\AppealModelAccessClass;
use App\Modules\File\AccessClass\ArticleModelAccessClass;
use App\Modules\File\AccessClass\CommentModelAccessClass;
use App\Modules\File\Models\FileModel;
use Illuminate\Support\Facades\Auth;

class CheckAccessToFileClass
{
    private $file;
    private $user;
    private $classMap = [
        ArticleModel::class => ArticleModelAccessClass::class,
        AppealModel::class => AppealModelAccessClass::class,
        CommentModel::class => CommentModelAccessClass::class,
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

    public function getAccessClass($prentClass)
    {
//        $class = $this->file->commentable_type;
        if (isset($this->classMap[$prentClass])) {
            return $this->classMap[$prentClass];
        }
        throw new \Exception('Ошибка определения прав на файл');
    }


    public function read()
    {
        $className = $this->file->commentable_type;
        $class = $this->getAccessClass($className);
        return (new $class([$this->file, $this->user]))->read();
    }


    public function write($object)
    {
        $class = get_class($object);
        if (isset($this->classMap[$class])) {
            return (new $this->classMap[$class]([$object, $this->user]))->write();
        }
        throw new \Exception('Ошибка определения прав на файл');
    }

}