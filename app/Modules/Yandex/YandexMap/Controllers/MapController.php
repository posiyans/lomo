<?php

namespace App\Modules\Yandex\YandexMap\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


// todo недоделано
class  MapController extends Controller
{

    private $offsetX = 0.00007; // смещение по X координат что лучше лоожилось на карту
    private $offsetY = 0.000035; // смещение по Y


    public function __invoke(
        Request $request
    ) {
        $cacheName = 'YandexMapCoordinates';
        $data = Cache::tags(['yandex'])->remember($cacheName, 6000, function () {
            $steads = SteadModel::all();
            $data = [];
            $centerX = [];
            $centerY = [];
            $countPoint = 0;
            $center = [30.313813, 59.935436];
            foreach ($steads as $item) {
                $temp = [
                    'id' => $item->id,
                    'size' => $item->size,
                    'number' => $item->number,
                    'color' => ['color' => '#ff0000', 'opacity' => '0.4'],
                    'coordinates' => null,
                ];
                if (isset($item->options['coordinates']) && !empty($item->options['coordinates'])) {
                    $ks = $item->options['coordinates'];
                    $l = [];
                    foreach ($ks as $k) {
                        $x = $k[0] + $this->offsetX;
                        $y = $k[1] + $this->offsetY;
                        $l[] = [$x, $y];
                        $centerX[] = $x;
                        $centerY[] = $y;
                        $countPoint++;
                    }
                    $temp['coordinates'] = $l;
                }
                $data[] = $temp;
            }
            if (count($centerX) > 0 && count($centerY) > 0) {
                $center = [array_sum($centerX) / count($centerX), array_sum($centerY) / count($centerY)];
            }
            return ['center' => $center, 'data' => $data];
        });

        return response($data);
    }

}
