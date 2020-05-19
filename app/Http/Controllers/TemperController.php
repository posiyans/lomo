<?php

namespace App\Http\Controllers;

use App\Models\Temper\TemperModel;
use Illuminate\Http\Request;
use Socialite;
use Illuminate\Support\Facades\Cache;

class TemperController extends Controller
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
        $temper = TemperModel::getTemper();
        return view('temper.index', compact('temper'));
    }


    /**
     * данные температуры, восхода и захода солнца за неделю
     * @return array
     */
    public function showGrafTemper(){
        $sunriseAndDusk = TemperModel::getSunriseAndDusk(7);
        return ['temper'=>TemperModel::getTemper(true)] + $sunriseAndDusk;
    }

    /**
     * данные температуры сейчас
     * @return array
     */
    public function showLocalTemper(){
        $temper = TemperModel::getTemper();
        return ['temper'=>$temper];
    }

    public function getNewWeatherProHD(){
        $value = Cache::remember('NewWeatherProHD', 60, function () {
            $link = env('WEATHER_LINL1',false);
            $response_data = false;
            if ($link) {
                $agent = 'WeatherProHD';
                $ch = curl_init($link);
                curl_setopt($ch, CURLOPT_USERAGENT, $agent);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept-Language: ru-ru,ru;q=0.8,en:q=0.3']);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection: keep-alive']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response_data = curl_exec($ch);
                curl_close($ch);
            }
            return $response_data;
        });
        $p = xml_parser_create();
        xml_parse_into_struct($p, $value, $vals, $index);
        xml_parser_free($p);
        $temper =[];
        $os =[];
        $osv =[];
        foreach ($vals as $val) {
            if ($val['tag'] == 'HOUR') {
                if (isset($val['attributes']['PRRR'])) {
                    $osv[] = ['time' => $val['attributes']['TIME'], 'temp' => $val['attributes']['PRRR']];
                }
                if (isset($val['attributes']['RRR'])) {
                    $os[] = ['time' => $val['attributes']['TIME'], 'temp' => $val['attributes']['RRR']];
                }
                if (isset($val['attributes']['TT'])) {
                    $temper[] = ['time' => $val['attributes']['TIME'], 'temp' => $val['attributes']['TT']];
                }
            }
        }
        return  json_encode(['temper'=>$temper, 'os'=>$os, 'osv'=>$osv]);

    }

//    public function testt(){
//        $value = Cache::remember('musers3', 6, function () {
//        $link = '/temper/testt';
//            $agent = 'WeatherProHD';
//            $ch = curl_init($link);
//            curl_setopt($ch, CURLOPT_USERAGENT, $agent);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept-Language: ru-ru,ru;q=0.8,en:q=0.3']);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection: keep-alive']);
////        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Host: android.weatherpro.meteogroup.de']);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            $response_data = curl_exec($ch);
////        if (curl_errno($ch) > 0) {
////            die('Ошибка curl: ' . curl_error($ch));
////        }
//            curl_close($ch);
//            return $response_data;
//        });
//        //echo $link;
////        echo '<pre>';
////        echo htmlspecialchars($value);
//        $p = xml_parser_create();
//        xml_parse_into_struct($p, $value, $vals, $index);
//        xml_parser_free($p);
////        echo "Массив index\n";
////        print_r($index);
////        echo "\nМассив vals\n";
////        print_r($vals);
//        $temper =[];
//        $os =[];
//        $osv =[];
//        foreach ($vals as $val) {
////            if($val['tag'] == 'HOUR' && $val['type'] == 'complete'){
//            if($val['tag'] == 'HOUR'){
//                unset($val['attributes']['INTERVAL'] );
//                if(isset($val['attributes']['PRRR'])){
////                    echo 'Вероятность осадков '. $val['attributes']['PRRR'] .' %<br>';
//                    $osv[] = ['time'=> $val['attributes']['TIME'], 'temp'=>$val['attributes']['PRRR']];
//                    unset($val['attributes']['PRRR'] );
//                }
//                if(isset($val['attributes']['RRR'])){
////                    echo 'Кол-во осадков '. $val['attributes']['RRR'] .' мм<br>';
//                    $os[] = ['time'=> $val['attributes']['TIME'], 'temp'=>$val['attributes']['RRR']];
//                    unset($val['attributes']['RRR'] );
//                }
//                if(isset($val['attributes']['PPP'])){
////                    echo 'Давление '. $val['attributes']['PPP'] .' гПа<br>';
//                    unset($val['attributes']['PPP'] );
//                }
//                if(isset($val['attributes']['FF'])){
////                    echo 'Ветер '. $val['attributes']['FF'] .' мили/ч<br>';
//                    unset($val['attributes']['FF'] );
//                }
//                if(isset($val['attributes']['FFG'])){
////                    echo 'Ветер порывы '. $val['attributes']['FFG'] .' мили/ч<br>';
//                    unset($val['attributes']['FFG'] );
//                }
//                if(isset($val['attributes']['DD'])){
////                    echo 'Направление '. $val['attributes']['DD'] .' <br>';
//                    unset($val['attributes']['DD'] );
//                }
//                if(isset($val['attributes']['TT'])){
////                    $temper[] = ['time'=> $val['attributes']['TIME'], 'temp'=>$val['attributes']['TT']];
//                    $temper[] = ['time'=> $val['attributes']['TIME'], 'temp'=>$val['attributes']['TT']];
//
//
//
////                    echo 'Температура '. $val['attributes']['TT'] .' С<br>';
////                    $v = 100 -5*($val['attributes']['TT'] - 25*$val['attributes']['TD']);
////                    $v = 100-5*($val['attributes']['TT'] - 0,25*($val['attributes']['TT']-$val['attributes']['TD']));
//                    $v = 100-5*($val['attributes']['TT']-$val['attributes']['TD']);
////                    echo 'Влажность ' . $v.'%<br>';
//                    unset($val['attributes']['TT'] );
//                }
//                if(isset($val['attributes']['TD'])){
////                    echo 'Точка росы TD ?? '. $val['attributes']['TD'] .' С<br>';
//                    unset($val['attributes']['TD'] );
//                }
//                if(isset($val['attributes']['SUN'])){
////                    echo 'продолжительность солнечного света '. $val['attributes']['SUN'] .' мин<br>';
//                    unset($val['attributes']['SUN'] );
//                }
////                print_r($val);
//            }
//        }
//        return  json_encode(['temper'=>$temper, 'os'=>$os, 'osv'=>$osv]);
//    }

}
