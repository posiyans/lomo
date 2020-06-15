<?php

namespace App\Http\Controllers\Yandex;

use App\Models\Temper\TemperModel;
use Illuminate\Http\Request;
use Socialite;
use Cache;
use App\Http\Controllers\Controller;

define('VK_API_ENDPOINT', 'https://api.vk.com/method/');
define('VK_API_VERSION', '5.103'); //Используемая версия API

class  ScheduleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
public function __construct()
    {
       //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $token = env('YANDEX_RASP_API_KEY', false);
        if ($token){
            $data = date('Y-m-d');
            if(isset($request->type)) {
                if ($request->type == 1){
                    $tomorrow  = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
                    $data = date('Y-m-d',$tomorrow);
                }
                if ($request->type == 2){
                    $data = false;
                }
            }
            $back = false;
            $key = 'yandexRasp'.$data;
            if(isset($request->back) && $request->back === 'true') {
                $back = true;
                $key = 'yandexRasp'.$data.'_';
            }
            $result = Cache::remember($key, 1400, function () use ($data, $back) {
                $token = env('YANDEX_RASP_API_KEY', false);
                $st1 = env('YANDEX_ST1', false);
                $st2 = env('YANDEX_ST2', false);
                if ($token && $st1 && $st2) {
                    if ($back){
                        $from = $st2;
                        $to = $st1;
                    } else {
                        $from = $st1;
                        $to = $st2;
                    }
                    $url = 'https://api.rasp.yandex.net/v3.0/search/?format=json&lang=ru_RU&page=1&transport_types=suburban';
                    if ($data) {
                        $url .= '&date=' . $data;
                    }
                    $url .= '&apikey='.$token;
                    $url .= '&from='.$from;
                    $url .= '&to='.$to;
                    return  file_get_contents($url);
                }
                return false;
            });

        }

        return  response()->json(json_decode($result));

    }

}
