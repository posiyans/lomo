<?php

namespace App\Modules\Article\Factories;

use App\Modules\Article\Models\CategoryModel;
use Illuminate\Database\Eloquent\Factories\Factory;

use function now;

class CategoryModelFactory extends Factory
{
    protected $model = CategoryModel::class;
    protected static $countCategory = 1;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence;
        return [
            'name' => 'Раздел ' . self::$countCategory++,
            'public' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

}
