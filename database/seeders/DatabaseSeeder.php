<?php

namespace Database\Seeders;

use Database\Seeders\Demo\ArticleModelDemoSeeder;
use Database\Seeders\Demo\BillingDemoSeeder;
use Database\Seeders\Demo\CategoryDemoSeeder;
use Database\Seeders\Demo\CommentsModelDemoSeeder;
use Database\Seeders\Demo\GardeningDemoSeeder;
use Database\Seeders\Demo\SteadModelDemoSeeder;
use Database\Seeders\Demo\UserModelDemoSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;


class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */


    public function run()
    {
        $demo = env('DEMO', false);
        $this->call([
            LaratrustSeeder::class,
            AppealTypeSeeder::class,
            OwnerUserFieldSeeder::class,
            /**
             * доп
             */
//            CategorySeeder::class,
//            SiteMenuSeeder::class,


        ]);
        if ($demo) {
            $this->call([
                CategorySeeder::class,
                SiteMenuSeeder::class,
                GardeningDemoSeeder::class,
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
