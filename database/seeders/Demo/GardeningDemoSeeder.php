<?php

namespace Database\Seeders\Demo;

use App\Modules\Gardening\Actions\UpdateGardeningFieldAction;
use Illuminate\Database\Seeder;

class GardeningDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        echo 'Demo ArticleModelSeeder ' . "\n";
        $fields = [
            'name' => [
                'value' => 'СНТ "Березка"'
            ],
            'full_name' => [
                'value' => 'Садоводческое некоммерческое товарищество собственников недвижимости «Березка»'
            ],
            'PersonalAcc' => [
                'value' => '00000000000000000000'
            ],
            'BankName' => [
                'value' => 'Северо-Западный банк'
            ],
            'BIC' => [
                'value' => '000000000'
            ],
            'CorrespAcc' => [
                'value' => '00000000000000000000'
            ],
            'PayeeINN' => [
                'value' => '0000000000'
            ]
        ];
        foreach ($fields as $key => $item) {
            (new UpdateGardeningFieldAction($key))->value($item['value'])->run();
        }
    }

}
