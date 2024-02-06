<?php

namespace Database\Seeders\Demo;

use App\Modules\Article\Models\ArticleModel;
use Illuminate\Database\Seeder;

class ArticleModelDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo 'Demo ArticleModelSeeder ' . "\n";
        ArticleModel::factory()->count(100)->create();
    }
}
