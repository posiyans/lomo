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
            'phones' => [
                'label' => 'Доп. номера',
                'type' => 'string',
            ],
            'general_phone' => [
                'label' => 'Номер телефона',
                'type' => 'phone'
            ],
            'email' => [
                'label' => 'Электронная почта для получения уведомлений',
                'type' => 'email',
            ],
            'address' => [
                'label' => 'Aдрес места жительства',
                'type' => 'string',
            ],
            'address_notifications' => [
                'label' => 'Почтовый адрес для получения уведомлений',
                'type' => 'string',
            ],
            'member' => [
                'label' => 'Членство в СНТ',
                'type' => 'boolean',
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
        $model->save();
    }


}
