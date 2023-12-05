<?php

namespace App\Modules\Comment\Repositories;

use App\Modules\Appeal\Modules\AppealModel;
use App\Modules\Article\Models\ArticleModel;
use App\Modules\Comment\Interfaces\CommentedObjectInterface;


/**
 * Поддеживаемые классы для комментариев
 */
class CommentTypeRepository
{
    private static $objectArray = [
        'article' => ArticleModel::class,
        'appeal' => AppealModel::class,
    ];

    public static function getKeys()
    {
        return array_keys(self::$objectArray);
    }

    public static function getClassName($type)
    {
        if (!isset(self::$objectArray[$type])) {
            throw new \Exception('Неподдерживаемый тип');
        }
        if (!is_subclass_of(self::$objectArray[$type], CommentedObjectInterface::class)) {
            throw new \Exception('Класс должен осуществлять CommentedObjectInterface');
        }
        return self::$objectArray[$type];
    }
}