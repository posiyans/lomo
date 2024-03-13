<?php

namespace App\Modules\File\Repositories;

use App\Modules\Appeal\Models\AppealModel;
use App\Modules\Article\Models\ArticleModel;
use App\Modules\Comment\Models\CommentModel;
use App\Modules\Stead\Models\SteadModel;

/***
 *
 * Сопоставление типа модели и Модели при загрузке файла
 */
class GetModelNameByType
{
    private $objectArray = [
        'article' => ArticleModel::class,
        'appeal' => AppealModel::class,
        'comment' => CommentModel::class,
        'stead' => SteadModel::class
    ];
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function run()
    {
        if (isset($this->objectArray[$this->type])) {
            return $this->objectArray[$this->type];
        }
        throw new \Exception('Тип модели не найден');
    }
}