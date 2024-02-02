<?php

namespace App\Modules\Stead\Factories;

use App\Modules\Stead\Models\SteadModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class SteadModelFactory extends Factory
{
    protected $model = SteadModel::class;

    protected static $i = 1;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $stead_count = SteadModel::all()->count();
        $number = self::$i++;
        if (rand(0, 10) > 8) {
            $number .= '/' . self::$i++;
        }
        return [
            'number' => $number,
            'size' => rand(580, 900),
            'options' => [
                'kadastr' => '47:23:2304006:112',
                'coordinates' => [
                    [30.479524846676256, 59.11028092721815],
                    [30.479465683631645, 59.11027189724136],
                    [30.478946008239777, 59.11024398638894],
                    [30.478984384268713, 59.11007487897361],
                    [30.479560024702774, 59.11011838727184],
                    [30.479524846676256, 59.11028092721815]
                ],
            ],
        ];
    }

}
