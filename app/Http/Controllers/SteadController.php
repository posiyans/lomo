<?php

namespace App\Http\Controllers;

use App\Models\Stead;
use App\Models\Temper\TemperModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Socialite;

class SteadController extends Controller
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
        return view('vue', ['template' => '<user-profile></user-profile>']);
    }

    public function list(Request $request)
    {
        $query = $request->input('query');
        $steads = Stead::query();
        if (isset($query) && !empty($query)){
          $steads->where('number','LIKE', '%'.$query.'%');
        }
        $data = $steads->get();
        return json_encode($data);
    }
}
