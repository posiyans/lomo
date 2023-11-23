<?php

namespace Database\Seeders;

use App\Modules\Article\Models\ArticleModel;
use Illuminate\Database\Seeder;

class ArticleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ArticleModel::factory()->count(10)->create();
    }
}
