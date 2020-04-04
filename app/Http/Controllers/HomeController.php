<?php

namespace App\Http\Controllers;

use App\Models\Temper\TemperModel;
use Illuminate\Http\Request;
use Socialite;

class HomeController extends Controller
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
//        $temper = TemperModel::getTemper();
        $temper = [];
        return view('welcome', compact('temper'));
    }

}
