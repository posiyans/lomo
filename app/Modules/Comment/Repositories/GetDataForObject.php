<?php

namespace App\Modules\Comment\Repositories;


use App\Modules\Appeal\Modules\AppealModel;
use App\Modules\Article\Models\ArticleModel;

/**
 * Получить класс для чеого возможны комментарии
 */
class GetDataForObject
{


    private $object;

    public function __construct($object)
    {
        $this->object = $object;
    }

    public function label()
    {
        switch (get_class($this->object)) {
            case ArticleModel::class:
                return "записи";
            case AppealModel::class:
                return "обращения";
        }
    }

    public function url()
    {
        switch (get_class($this->object)) {
            case ArticleModel::class:
                return "/article/show/" . $this->object->slug;
            case AppealModel::class:
                return "/appeal/show/" . $this->object->id;
        }
    }
}