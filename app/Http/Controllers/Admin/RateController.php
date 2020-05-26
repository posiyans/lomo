<?php

namespace App\Http\Controllers\Admin;

use App\Models\MeteringDevice;
use App\Models\Rate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $models = MeteringDevice::where('type_id', $request->type)->get();
        foreach ($models as $model) {
             $model->rateNow();
        }
        return ['data'=>$models];

        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->ability('superAdmin', 'edit-rate')) {
            if ($request->id) {
                $rate = MeteringDevice::find($request->id);
                $rate->type_id = $request->type_id;
                $rate->name = $request->name;
                $rate->enable = $request->enable;
                $rate->discription = $request->discription;
                $rate->save();
                if ($request->rate) {
                    $r = new Rate();
                    $r->device_id = $rate->id;
                    $r->ratio_a = $request->rate['ratio_a'];
                    $r->ratio_b = $request->rate['ratio_b'];
                    $r->discription = $request->rate['discription'];
                    $r->save();
                }
                return json_encode(['status' => true, 'data' => $rate]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
