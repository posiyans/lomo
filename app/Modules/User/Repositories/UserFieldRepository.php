<?php

namespace App\Modules\User\Repositories;


class UserFieldRepository
{
    //todo переделать на базу

    private static $arrayFields = [
        'last_name' => [
            'rules' => [
                'string',
                'max:255'
            ],
            'label' => '',
        ],
        'name' => [
            'rules' => [
                'string',
                'max:255'
            ],
            'label' => '',
        ],
        'middle_name' => [
            'rules' => [
                'string',
                'max:255'
            ],
            'label' => '',
        ],
        'phone' => [
            'rules' => [
                'string',
                'max:255'
            ],
            'optional' => true,
            'type' => 'string',
            'label' => 'Телефон',
        ],
        'adres' => [
            'rules' => [
                'string',
                'max:255'
            ],
            'optional' => true,
            'type' => 'string',
            'label' => 'Адрес регистрации/почтовый адрес',
        ],
        'telegram' => [
            'rules' => [
                'numeric'
            ],
            'optional' => true,
            'type' => 'number',
            'label' => 'Телеграм ID',
        ],
        'email' => [
            'rules' => [
                'email:rfc,dns',
                'unique:App\Modules\User\Models\UserModel',
            ],
            'label' => '',
        ]
    ];

    public static function getFields()
    {
        return self::$arrayFields;
    }

    public static function getKeys()
    {
        return array_keys(self::$arrayFields);
    }

    public static function getOptionalKeys()
    {
        return array_keys(
            array_filter(self::$arrayFields, function ($item) {
                return $item['optional'] ?? '';
            })
        );
    }

    public static function isOptional($field)
    {
        return self::$arrayFields[$field]['optional'] ?? false;
    }


    public static function getRules($field)
    {
        return self::$arrayFields[$field]['rules'] ?? [];
    }

    public static function getLabel($field)
    {
        return self::$arrayFields[$field]['label'] ?? [];
    }


}