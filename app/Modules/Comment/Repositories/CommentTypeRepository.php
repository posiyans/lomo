<?php

namespace App\Modules\Comment\Repositories;

use App\Modules\Appeal\Modules\AppealModel;
use App\Modules\Article\Models\ArticleModel;
use App\Modules\Comment\Classes\AppealComments;
use App\Modules\Comment\Classes\ArticleComments;


/**
 * Поддеживаемые классы для комментариев
 */
class CommentTypeRepository
{
    private static $objectArray = [
        'article' => [
            'className' => ArticleModel::class,
            'mapClass' => ArticleComments::class,
        ],
        'appeal' => [
            'className' => AppealModel::class,
            'mapClass' => AppealComments::class,
        ]
    ];

    public static function getKeys()
    {
        return array_keys(self::$objectArray);
    }

    public static function getClassName($type)
    {
        if (!isset(self::$objectArray[$type]['className'])) {
            throw new \Exception('Неподдерживаемый тип');
        }
        return self::$objectArray[$type]['className'];
    }

    public static function getCommentedRoleClass($object)
    {
        $mapClass = '';
        $class = get_class($object);
        foreach (self::$objectArray as $item) {
            if ($item['className'] == $class) {
                $mapClass = $item['mapClass'];
            }
        }
        if ($mapClass) {
            return $mapClass;
        }
        throw new \Exception('Неподдерживаемый тип');
    }


    public static function getCommentedRoleObject($object)
    {
        $class = self::getCommentedRoleClass($object);
        return new $class($object);
    }

}