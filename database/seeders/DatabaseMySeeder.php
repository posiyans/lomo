<?php

use Illuminate\Database\Seeder;
use App\Models\MeteringDevice;
use App\Models\Gardening;
use App\Models\Stead;
use App\Models\ReceiptType;
use App\Models\Rate;

class DatabaseMySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // проверка на повторный запуск
        $gardient = Gardening::all();
        if ($gardient) {
            echo "Повторный запуск \n";
            return '';
        }

        $gardient = new Gardening;
        $gardient->name = 'СНТ "ЛОМО"';
        $gardient->full_name = 'Садоводческое некоммерческое товарищество собственников недвижимости «ЛОМО» массива «Чаща»';
        $gardient->PersonalAcc = '40703810955080102786';
        $gardient->BankName = 'Северо-Западный банк ПАО «Сбербанка России» г.Санкт-Петербург';
        $gardient->BIC = '044030653';
        $gardient->CorrespAcc = '30101810500000000653';
        $gardient->PayeeINN = '4719011680';
        $gardient->save();


        // for ($i = 1; $i < 550; $i++) {
        //     $stead = new Stead();
        //     $stead->gardening_id = $gardient->id;
        //     $stead->number = $i;
        //     $stead->save();
        // }
        $row = 1;
        if (($handle = fopen("./database/seeds/data.csv", "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                $num = count($data);
                echo "$num полей в строке $row: \n";
                $row++;
                for ($c = 0; $c < $num; $c++) {
                    echo $data[$c] . "\n";
                }
                if ($data[2] > 0) {
                    $stead = new Stead();
                    $stead->gardening_id = $gardient->id;
                    $stead->number = str_replace("'", '', $data[1]);
                    $stead->size = str_replace("'", '', $data[2]);
                    $stead->descriptions = ['fio' => str_replace("'", '', $data[3])];
                    $stead->save();
                }
            }
            fclose($handle);
        }

        $receipt = new ReceiptType;
        $receipt->name = 'Оплата за электроэнергию';
        $receipt->payment_period = 'month';
        $receipt->depends = 2;
        $receipt->save();
        $device = new MeteringDevice;
        $device->type_id = $receipt->id;
        $device->name = 'День';
        $device->description = 'элентроэнергии по дневному тарифу';
        $device->enable = 1;
        $device->save();
        $rate = new Rate;
        $rate->device_id = $device->id;
        $rate->ratio_a = 5.39;
        $rate->ratio_b = 0;
        $rate->description = '5.39 руб*кВт/ч';
        $rate->save();
        $device = new MeteringDevice;
        $device->type_id = $receipt->id;
        $device->name = 'Ночь';
        $device->description = 'элентроэнергии по ночному тарифу';
        $device->enable = 1;
        $device->save();
        $rate = new Rate;
        $rate->device_id = $device->id;
        $rate->ratio_a = 2.916;
        $rate->ratio_b = 0;
        $rate->description = '2.916 руб*кВт/ч';
        $rate->save();

        $receipt = new ReceiptType;
        $receipt->name = 'Взносы';
        $receipt->payment_period = 'year';
        $receipt->depends = 1;
        $receipt->save();

        $device = new MeteringDevice;
        $device->type_id = $receipt->id;
        $device->name = 'Членский взнос';
        $device->description = 'Оплата членского взноса.';
        $device->enable = 1;
        $device->save();
        $rate = new Rate;
        $rate->device_id = $device->id;
        $rate->ratio_a = 598;
        $rate->ratio_b = 0;
        $rate->description = '598 руб с сотки';
        $rate->save();

//            $indication = new InstrumentReadingModel;
//        $indication
        $device = new MeteringDevice;
        $device->type_id = $receipt->id;
        $device->name = 'Целевой взнос';
        $device->description = 'Оплата целевого взноса';
        $device->enable = 1;
        $device->save();
        $rate = new Rate;
        $rate->device_id = $device->id;
        $rate->ratio_a = 326;
        $rate->ratio_b = 0;
        $rate->description = '326 руб с сотки';
        $rate->save();
        $device = new MeteringDevice;
        $device->type_id = $receipt->id;
        $device->name = 'Земельный налог';
        $device->description = 'Оплата земельного налога';
        $device->enable = 1;
        $device->save();
        $rate = new Rate;
        $rate->device_id = $device->id;
        $rate->ratio_a = 31;
        $rate->ratio_b = 0;
        $rate->description = '31 руб с сотки';
        $rate->save();
        $device = new MeteringDevice;
        $device->type_id = $receipt->id;
        $device->name = 'Вывоз мусора';
        $device->description = 'Оплата вывоза мусора';
        $device->enable = 1;
        $device->save();
        $rate = new Rate;
        $rate->device_id = $device->id;
        $rate->ratio_a = 0;
        $rate->ratio_b = 578;
        $rate->description = '578 руб с участка';
        $rate->save();
    }
}
