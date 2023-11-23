<?php

namespace App\Modules\File\Classes;

use App\Modules\File\Models\FileModel;
use App\Modules\File\Repositories\GetObjectForFileRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Проверить на право удалить фаил
 *
 */
class CheckAccessToDeleteFileClass
{
    private $file;

    public function __construct(FileModel $file)
    {
        $this->file = $file;
    }

    public function run()

    {
        try {
            $model = (new GetObjectForFileRepository($this->file))->run();
            $class = get_class($model);
            switch ($class) {
                case 'App\Modules\Article\Models\ArticleModel':
                    return $this->article($model);
            }
        } catch (\Exception $e) {
            //  Если модель не найдена, а ты владелец файла то можешь его удалить
            $user = Auth::user();
            if ($this->file->user_id == $user->id) {
                return true;
            }
        }
        throw new \Exception('Ошибка определения прав на файл');
    }

    /**
     * Удалять файлы статьи могут только пользователи с правом редактирования статей
     * @param $article
     * @return bool
     */
    private function article($article)
    {
        $user = Auth::user();
        if ($user && $user->ability('superAdmin', 'article-edit')) {
            return true;
        }
        return false;
    }
}