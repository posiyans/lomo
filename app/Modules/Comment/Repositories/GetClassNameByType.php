<?php

namespace App\Modules\Comment\Repositories;

use App\Modules\Appeal\Modules\AppealModel;
use App\Modules\Article\Models\ArticleModel;


/**
 * Получить класс для чеого возможны комментарии
 */
class GetClassNameByType
{
    private $objectArray = [
        'article' => ArticleModel::class,
        'appeal' => AppealModel::class,
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
        } else {
            throw new \Exception('Неподдерживаемый тип');
        }
    }
}