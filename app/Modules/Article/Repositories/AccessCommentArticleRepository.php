<?php

namespace App\Modules\Article\Repositories;

class AccessCommentArticleRepository
{

    private static $access = [
        [
            'id' => 0,
            'label' => 'отключены',
            'roles' => []
        ],
        [
            'id' => 1,
            'label' => 'разрешены всем',
            'roles' => ['user']
        ],
        [
            'id' => 2,
            'label' => 'только собственники',
            'roles' => ['owner']
        ],
    ];

    public static function getAccess()
    {
        return self::$access;
    }

    public static function getAccessKeys()
    {
        return array_map(function ($item) {
            return $item['id'];
        }, self::$access);
    }


}