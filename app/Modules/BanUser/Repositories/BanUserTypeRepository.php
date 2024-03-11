<?php

namespace App\Modules\BanUser\Repositories;

use App\Modules\Article\Models\ArticleModel;
use App\Modules\Comment\Models\CommentModel;

/**
 * Типы возможных банов
 */
class BanUserTypeRepository
{

    private static $type = [
        'all' => [
            'className' => null,
            'label' => 'бан на все'
        ],
        'comment' => [
            'className' => CommentModel::class,
            'label' => 'бан на комментарии'
        ],
        'article' => [
            'className' => ArticleModel::class,
            'label' => 'бан на добавление  и комментирование статей'
        ]
    ];


    public static function getTypeKey()
    {
        return array_keys(self::$type);
    }

    public static function getTypeClass($type)
    {
        return isset(self::$type[$type]) ? self::$type[$type]['className'] : null;
    }

    public static function getKeyForClass($className)
    {
        $type = 'all';
        foreach (self::$type as $key => $value) {
            if ($value['className'] == $className) {
                $type = $key;
            }
        }
        return $type;
    }


    public static function getLabelForClass($className)
    {
        return self::getLabel(self::getKeyForClass($className));
    }

    public static function getLabel($type)
    {
        return self::$type[$type] ? self::$type[$type]['label'] : 'бан на все';
    }


}