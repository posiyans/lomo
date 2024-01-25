<?php

namespace App\Modules\Yandex\YandexMap\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Http\Request;


// todo недоделано
class  MapController extends Controller
{
    public function __invoke(Request $request)
    {
        $steads = SteadModel::all();
        $data = [];
        foreach ($steads as $item) {
            if (isset($item->options['geodata'])) {
                $js = $item->options['geodata'];
                $temp = [
                    'center' => [$js['properties']['center']['y'], $js['properties']['center']['x']],
                    'size' => $item->size,
                    'number' => $item->number,
                    'id' => $item->id,
                ];
                $temp['color'] = ['color' => '#ff0000', 'opacity' => '0.4'];
//                if ($item->number == 400) {
//                    $temp['color'] = ['color' => '#00ff00', 'opacity' => '0.4'];
//                } else {
//                    $temp['color'] = ['color' => '#ff0000', 'opacity' => '0.4'];
//                }
//                $temp['cols'] = '#00ff00';
                if (isset($js['geometry']['coordinates'])) {
                    $ks = $js['geometry']['coordinates'][0][0];
                    $l = [];
                    foreach ($ks as $k) {
                        $l[] = [$k[0] + 0.00006000100000, $k[1] + 0.00004500100000];
                    }
                    $temp['krd'] = $l;
                }
                $data[] = $temp;
            }
        }
        return json_encode($data);
    }

}
