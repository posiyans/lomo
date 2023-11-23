<?php

namespace App\Modules\File\Repositories;

use App\Modules\Article\Models\ArticleModel;

/***
 *
 * Сопоставление типа модели и Модели при загрузке файла
 */
class GetModelNameByType
{
    private $objectArray = [
        'article' => ArticleModel::class
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