<?php

namespace Database\Seeders\Demo;

use App\Modules\Article\Models\CategoryModel;
use App\Modules\SiteMenu\Actions\CreateSiteMenuAction;
use Illuminate\Database\Seeder;

class CategoryDemoSeeder extends Seeder
{


    public function run()
    {
        echo 'Demo CategorySeeder ' . "\n";
        $i = 10;
        $categorys = CategoryModel::factory()->count(10)->create();
        $primary = (new CreateSiteMenuAction('Demo'))->sort($i++)->run();
        foreach ($categorys as $category) {
            (new CreateSiteMenuAction($category->name))->path('/article/list/' . $category->id)->sort($i++)->parent($primary)->run();
        }
    }

}
