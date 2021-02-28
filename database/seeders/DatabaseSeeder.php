<?php

namespace Database\Seeders;

use Database\Seeders\LaratrustSeeder;
use Illuminate\Database\Seeder;
use App\Models\MeteringDevice;
use App\Models\ReceiptType;
use App\Models\Rate;


class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LaratrustSeeder::class);
    }
}
