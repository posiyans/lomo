<?php

namespace Database\Seeders\Demo;

use App\Modules\Comment\Models\CommentModel;
use Illuminate\Database\Seeder;

class CommentsModelDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo get_class($this) . "\n";
        CommentModel::factory()->count(300)->create();
    }
}
