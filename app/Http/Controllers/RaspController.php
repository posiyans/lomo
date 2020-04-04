<?php

namespace App\Http\Controllers;

use App\Models\Temper\TemperModel;
use Illuminate\Http\Request;
use Socialite;
use Cache;
define('VK_API_ENDPOINT', 'https://api.vk.com/method/');
define('VK_API_VERSION', '5.103'); //Используемая версия API

class  RaspController extends Controller
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
    public function index()
    {
        $temper = '';
        return view('rasp.index', compact('temper'));
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function get()
    {

        $token = env('YANDEX_RASP_API_KEY', false);
//        $from = 's9602496';
//        $to = 's9602670';
        $result = 'rasp';

        if ($token){
            $result = Cache::remember('key', 10, function () {
                $token = env('YANDEX_RASP_API_KEY', false);
                $from = 's9602496';
                $to = 's9602670';
                $url = 'https://api.rasp.yandex.net/v3.0/search/?format=json&lang=ru_RU&page=1&transport_types=suburban';
                $url .= '&apikey='.$token;
                $url .= '&from='.$from;
                $url .= '&to='.$to;
                return file_get_contents($url);
            });

            // $result = $url;
        }

        return  response()->json(json_decode($result));


    }

}
