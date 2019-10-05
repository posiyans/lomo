<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;

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
        $temper = TemperController::getTemper();
        return view('temper.index', compact('temper'));
    }

    public static function getTemper($week = false){
        $url = env('GET_TEMPER_URL', false);
        if ($url){
            try {
                if ($week) {
                   $url.='?d=1';
                }
                $temper = file_get_contents($url);
                $temper =json_decode($temper);
            } catch ( \Exception $e){
                $temper = false;
                //echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
            }
            if ($week) {
                return $temper;
            }
            if ($temper and isset($temper->temp)){
                $temper->time = substr($temper->time, 11, 5);
                $temper->temp = round(floatval($temper->temp),1);
            }else{
                $temper = false;
            }
        }
        return $temper;
    }


    public function showGrafTemper(){

        return $this->getTemper(true);
    }

}
