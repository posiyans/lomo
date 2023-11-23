<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Stead;
use Illuminate\Http\Request;
use Socialite;


class SteadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//       $this->middleware('auth');
//    }


    public function list(Request $request)
    {
        $query = $request->input('query');
        $stead_id = $request->input('stead_id');
        $steads = Stead::query();
        if (isset($query) && !empty($query)) {
            $steads->where('number', 'LIKE', '%' . $query . '%');
        }
        if (isset($stead_id) && is_numeric($stead_id)) {
            $steads->where('id', $stead_id);
        }
        $data = $steads->select(['id', 'number', 'size'])->get();

        return json_encode($data);
    }
}
