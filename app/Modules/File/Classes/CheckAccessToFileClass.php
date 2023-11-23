<?php

namespace App\Modules\File\Classes;

use App\Modules\File\Models\FileModel;
use App\Modules\File\Repositories\GetObjectForFileRepository;
use App\Modules\User\Models\UserModel;
use Illuminate\Support\Facades\Auth;

class CheckAccessToFileClass
{
    private $file;
    private $user;

    public function __construct(FileModel $file)
    {
        $this->file = $file;
        $this->user = Auth::user() ? Auth::user() : null;
    }

    public function forUser(UserModel $user): CheckAccessToFileClass
    {
        $this->user = $user;
        return $this;
    }

    public function run()

    {
        $class = $this->file->commentable_type;
        switch ($class) {
            case 'App\Modules\Article\Models\ArticleModel':
                return $this->article();
        }
        throw new \Exception('Ошибка определения прав на файл');
    }

    private function article(): bool
    {
        if ($this->user && $this->file->user_id == $this->user->id) {
            return true;
        }
        if ($this->user && $this->user->ability('superAdmin', 'article-edit')) {
            return true;
        }
        $model = (new GetObjectForFileRepository($this->file))->run();
        if ($model->public == 1) {
            return true;
        }
        return false;
    }
}