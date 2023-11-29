<?php

namespace Database\Seeders;

use App\Modules\Owner\Models\OwnerUserFiledModel;
use Illuminate\Database\Seeder;

class OwnerUserFieldSeeder extends Seeder
{
    /**
     * Заполняем начальное меню сайта
     *
     * @return  void
     */
    public function run()
    {
        echo 'OwnerUserFieldSeeder ' . "\n";
        $fields = [
            'surname' => [
                'label' => 'Фамилия',
                'type' => 'string',
                'options' => [
                    'readonly' => true,
                    'rules' => [
                        'required'
                    ]
                ]
            ],
            'name' => [
                'label' => 'Имя',
                'type' => 'string',
                'options' => [
                    'readonly' => true,
                ]
            ],
            'middle_name' => [
                'label' => 'Отчетство',
                'type' => 'string',
                'options' => [
                    'readonly' => true,
                ]
            ],
            'date_birth' => [
                'label' => 'Дата рождения',
                'type' => 'date',
                'options' => [
                    'readonly' => true,
                ]
            ],
            'general_phone' => [
                'label' => 'Номер телефона',
                'type' => 'phone',
                'options' => [
                    'readonly' => true,
                ]
            ],
            'phones' => [
                'label' => 'Доп. номера',
                'type' => 'string',
                'options' => [
                    'readonly' => true,
                ]
            ],
            'email' => [
                'label' => 'Электронная почта для получения уведомлений',
                'type' => 'string',
                'options' => [
                    'readonly' => true,
                    'rules' => [
                        'isEmail'
                    ]
                ]
            ],
            'address' => [
                'label' => 'Адрес места жительства',
                'type' => 'string',
                'options' => [
                    'readonly' => true,
                ]
            ],
            'address_notifications' => [
                'label' => 'Почтовый адрес для получения уведомлений',
                'type' => 'string',
                'options' => [
                    'readonly' => true,
                ]
            ],
            'member' => [
                'label' => 'Является собственик членом СНТ',
                'type' => 'boolean',
                'options' => [
                    'readonly' => true,
                    'rules' => [
                        'required'
                    ],

                ]
            ]

        ];

        foreach ($fields as $key => $item) {
            $this->createModel($key, $item);
        }
    }

    protected function createModel($name, $item)
    {
        $model = new OwnerUserFiledModel();
        $model->name = $name;
        $model->label = $item['label'];
        $model->type = $item['type'];
        $model->options = $item['options'] ?? [];
        $model->save();
    }


}
