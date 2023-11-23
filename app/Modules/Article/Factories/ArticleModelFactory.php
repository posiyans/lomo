<?php

namespace App\Modules\Article\Factories;

use App\Models\Article\CategoryModel;
use App\Modules\Article\Models\ArticleModel;
use App\Modules\User\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use function now;

class ArticleModelFactory extends Factory
{
    protected $model = ArticleModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence;
        $uid = Str::uuid();
        $category = CategoryModel::query()->whereNull('menu_name')->get()->random();
        return [
            'title' => $category->name . ' ' . $title,
            'resume' => $this->faker->realText,
            'text' => $this->faker->paragraph,
            'category_id' => $category->id,
            'public' => 1,
            'news' => 1,
            'allow_comments' => 1,
            'publish_time' => now(),
            'user_id' => UserModel::all()->random()->id,
            'slug' => Str::slug($title) . '_' . $uid,
            'uid' => $uid,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

}
