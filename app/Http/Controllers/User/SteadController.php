<?php

namespace App\Http\Controllers\User;

use App\Models\Stead;
use App\Models\Temper\TemperModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Socialite;
use App\Http\Controllers\Controller;


class SteadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('auth');
    }


    public function list(Request $request)
    {
        $query = $request->input('query');
        $steads = Stead::query();
        if (isset($query) && !empty($query)){
          $steads->where('number','LIKE', '%'.$query.'%');
        }
        $data = $steads->select(['id', 'number'])->get();

        return json_encode($data);
    }
}
