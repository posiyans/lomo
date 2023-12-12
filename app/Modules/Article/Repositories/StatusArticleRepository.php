<?php

namespace App\Modules\Article\Repositories;

class StatusArticleRepository
{

    private static $status = [
        [
            'id' => 1,
            'label' => 'черновик',
            'color' => '#cecece'
        ],
        [
            'id' => 2,
            'label' => 'опубликовано',
            'color' => '#005500'
        ],
        [
            'id' => 3,
            'label' => 'на модерации',
            'color' => '#0000ff'
        ],
        [
            'id' => 4,
            'label' => 'на доработке',
            'color' => '#cecece'
        ],
        [
            'id' => 5,
            'label' => 'опубликовано только для собственников',
            'color' => '#00cece'
        ],
        [
            'id' => 9,
            'label' => 'отказано',
            'color' => '#ff0000'
        ],
    ];

    public static function getStatus()
    {
        return self::$status;
    }

    public static function getStatusKeys()
    {
        return array_map(function ($item) {
            return $item['id'];
        }, self::$status);
    }


}