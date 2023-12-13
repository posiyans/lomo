<?php

namespace App\Modules\Stead\Repositories;

use App\Modules\Stead\Models\SteadModel;

class SteadFiledRepository
{

    private static $fieldArray = [
        'number' => [
            'label' => 'Номер участка',
            'type' => 'string',
            'rules' => [
                'string',
                'unique:' . SteadModel::class . ',number',
            ]
        ],
        'size' => [
            'label' => 'Размер',
            'type' => 'number',
            'rules' => [
                'numeric'
            ]
        ],
        'kadastr' => [
            'label' => 'Кадастровый номер',
            'optional' => true,
            'type' => 'string',
            'rules' => [
                'string'
            ]
        ],
    ];

    public static function getKeys()
    {
        return array_keys(self::$fieldArray);
    }

    public static function getRule($field_name)
    {
        return self::$fieldArray[$field_name]['rules'] ?? [];
    }

    public static function isOptional($field_name)
    {
        return self::$fieldArray[$field_name]['optional'] ?? false;
    }


}