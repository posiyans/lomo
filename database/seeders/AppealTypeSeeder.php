<?php

namespace Database\Seeders;

use App\Modules\Appeal\Modules\AppealTypeModel;
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
            'Участок'
        ];

        foreach ($fields as $item) {
            $this->createModel($item);
        }
    }

    protected function createModel($item)
    {
        $model = new AppealTypeModel();
        $model->label = $item;
        $model->save();
    }


}
