<?php
namespace App\Modules\File\Classes;

use App\Modules\File\Models\FileModel;
use App\Modules\File\Repositories\GetObjectForFileRepository;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Nonstandard\Uuid;

class CheckAccessToFileClass
{
    private $file;

    public function __construct(FileModel $file)
    {
        $this->file = $file;
    }

    public function run()

    {
        $model = (new GetObjectForFileRepository($this->file))->run();
        $class  = get_class($model);
        switch ($class) {
            case 'App\Modules\Article\Models\ArticleModel':
                return $this->article($model);
        }
        throw new \Exception('Ошибка определения прав на файл');
    }

    private function article($model)
    {
        if ($model->public == 1) {
            return true;
        }
        $user = Auth::user();
        if ($user && $user->ability('superAdmin', 'edit-article')) {
            return true;
        }
        if ($user && $this->file->user_id == $user->id) {
            return true;
        }
        return false;
    }
}