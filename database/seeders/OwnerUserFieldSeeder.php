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
                    'rules' => [
                        'required'
                    ]
                ]
            ],
            'name' => [
                'label' => 'Имя',
                'type' => 'string',
            ],
            'middle_name' => [
                'label' => 'Отчетство',
                'type' => 'string',
            ],
            'date_birth' => [
                'label' => 'Дата рождения',
                'type' => 'date'
            ],
            'general_phone' => [
                'label' => 'Номер телефона',
                'type' => 'phone'
            ],
            'phones' => [
                'label' => 'Доп. номера',
                'type' => 'string',
            ],
            'email' => [
                'label' => 'Электронная почта для получения уведомлений',
                'type' => 'string',
                'options' => [
                    'rules' => [
                        'isEmail'
                    ]
                ]
            ],
            'address' => [
                'label' => 'Адрес места жительства',
                'type' => 'string',
            ],
            'address_notifications' => [
                'label' => 'Почтовый адрес для получения уведомлений',
                'type' => 'string',
            ],
            'member' => [
                'label' => 'Является собственик членом СНТ',
                'type' => 'boolean',
                'options' => [
                    'rules' => [
                        'required'
                    ]
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
