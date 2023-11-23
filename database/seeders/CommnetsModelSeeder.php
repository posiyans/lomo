<?php

namespace Database\Seeders;

use App\Modules\Comment\Models\CommentModel;
use Illuminate\Database\Seeder;

class CommnetsModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = CommentModel::factory()->count(10)->create();
    }
}
