<?php

namespace Database\Seeders;

use App\Modules\Appeal\Models\AppealTypeModel;
use Illuminate\Database\Seeder;

class AppealTypeSeeder extends Seeder
{
    /**
     * Заполняем начальное меню сайта
     *
     * @return  void
     */
    public function run()
    {
        echo 'AppealTypeSeeder ' . "\n";
        $fields = [
            [
                'label' => 'Участок',
                'description' => 'Обращение связанные с вашим участком'
            ]

        ];

        foreach ($fields as $item) {
            $this->createModel($item);
        }
    }

    protected function createModel($item)
    {
        $model = new AppealTypeModel();
        $model->label = $item['label'];
        $model->description = $item['description'] ?? '';
        $model->public = true;
        $model->save();
    }


}
