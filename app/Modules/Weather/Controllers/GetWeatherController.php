<?php

namespace App\Modules\Weather\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

use function response;

/**
 * Получить информацию о пользователе
 *
 */
class GetWeatherController extends Controller
{

    public function __invoke(Request $request)
    {
        try {
            $all = $request->all;
            $value = Cache::remember('NewWeatherProHD', 1800, function () {
                $link = env('WEATHER_LINL1', false);
                if ($link) {
                    $userAgent = 'WeatherProHD';
                    $response = Http::withUserAgent($userAgent)
                        ->acceptJson()
                        ->withHeaders([
                            'Accept-Language' => 'ru-ru,ru;q=0.8,en:q=0.3',
                            'Connection' => 'keep-alive',
                        ])
                        ->get($link);
                    return $response->body();
                }
                return '';
            });
            $xml = simplexml_load_string($value);
            $json = json_encode($xml->obs->lastObs);
            $array = json_decode($json, true);
            $data_now = $array['@attributes'];
            $temper = [];
            foreach ($xml->forecast->hours[0] as $item) {
                $json = json_encode($item[0]);
                $array = json_decode($json, true);
                $val = $array['@attributes'];
                $temper[] = $val;
            }
            if ($all) {
                return response(['temper' => $temper, 'now' => $data_now]);
            } else {
                return response(['data' => $data_now]);
            }
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 410);
        }
    }


}
