<?php

namespace Database\Seeders;

use App\Models\MeteringDevice;
use App\Models\Rate;
use App\Models\ReceiptType;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            LaratrustSeeder::class
        ]);
    }
}
