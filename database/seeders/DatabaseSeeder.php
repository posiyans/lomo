<?php

namespace Database\Seeders;

use Database\Seeders\Demo\ArticleModelDemoSeeder;
use Database\Seeders\Demo\BillingDemoSeeder;
use Database\Seeders\Demo\CategoryDemoSeeder;
use Database\Seeders\Demo\CommentsModelDemoSeeder;
use Database\Seeders\Demo\SteadModelDemoSeeder;
use Database\Seeders\Demo\UserModelDemoSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;


class DatabaseSeeder extends Seeder
{
    private $demo = true;

    /**
     * Seed the application's database.
     *
     * @return void
     */


    public function run()
    {
        $this->call([
            LaratrustSeeder::class,
            AppealTypeSeeder::class,
            OwnerUserFieldSeeder::class,
            /**
             * доп
             */
            CategorySeeder::class,
            SiteMenuSeeder::class,


        ]);
        if ($this->demo) {
            $this->call([
                UserModelDemoSeeder::class,
                SteadModelDemoSeeder::class,
                BillingDemoSeeder::class,
                CategoryDemoSeeder::class,
                ArticleModelDemoSeeder::class,
                CommentsModelDemoSeeder::class,


            ]);
        }
        Cache::flush();
    }
}
