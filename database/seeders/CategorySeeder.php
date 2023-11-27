<?php

namespace Database\Seeders;

use App\Modules\Article\Models\CategoryModel;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Заполняем начальное меню сайта
     *
     * @return  void
     */
    public function run()
    {
        echo 'CategorySeeder ' . "\n";
        $options = [
            'name' => 'Новости',
            'public' => true
        ];
        CategoryModel::create($options);
    }


}
