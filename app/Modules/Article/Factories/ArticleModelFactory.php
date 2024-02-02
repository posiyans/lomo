<?php

namespace App\Modules\Article\Factories;

use App\Modules\Article\Models\ArticleModel;
use App\Modules\Article\Models\CategoryModel;
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
        $category = CategoryModel::query()->get()->random();
        return [
            'title' => $title,
            'uid' => $uid,
            'user_id' => UserModel::all()->random()->id ?? 1,
            'resume' => $this->faker->realText,
            'text' => nl2br($this->faker->paragraphs(5, true)),
            'category_id' => $category->id,
            'status' => 2,
            'allow_comments' => 2,
            'slug' => Str::slug($title) . '_' . $uid,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

}
