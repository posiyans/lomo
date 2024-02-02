<?php

namespace App\Modules\User\Factories;

use App\Modules\User\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use function now;

class UserModelFactory extends Factory
{
    protected $model = UserModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fio = explode(' ', $this->faker->name);
        return [
            'name' => $fio[1],
            'last_name' => $fio[0],
            'middle_name' => $fio[2],
            'email' => $this->faker->unique()->safeEmail,
            'uid' => Str::uuid(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'options' => [
                'adres' => $this->faker->address,
                'phone' => $this->faker->phoneNumber,
            ],
        ];
    }

}
