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
        $url = env('GET_TEMPER_URL', false);
        if ($url){
            try {
                $temper =file_get_contents($url.'?d=1');
                $temper =json_decode($temper);
            } catch ( \Exception $e){
                //echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
            }
            if ($temper == null){
               $temper = false;
            }else{
                $temper->time = substr($temper->time, 11, 5);
                $temper->temp = round(floatval($temper->temp),1);
            }
            //dump($temper);
            //dump(compact('temper'));


        }

        return view('welcome', compact('temper'));
    }


}
