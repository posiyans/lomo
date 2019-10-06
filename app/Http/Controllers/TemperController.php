<?php

namespace App\Http\Controllers;

use App\Models\Temper\TemperModel;
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
        $temper = TemperModel::getTemper();
        return view('temper.index', compact('temper'));
    }


    /**
     * данные температуры, восхода и захода солнца за неделю
     * @return array
     */
    public function showGrafTemper(){
        $sunriseAndDusk = TemperModel::getSunriseAndDusk(7);
        return ['temper'=>TemperModel::getTemper(true)]+$sunriseAndDusk;
    }

}
