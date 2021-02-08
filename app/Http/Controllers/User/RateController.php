<?php

namespace App\Http\Controllers\User;

use App\Models\Receipt\MeteringDevice;
use App\Models\Receipt\Rate;
use App\Models\Receipt\ReceiptType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RateController extends Controller
{
    /**
     * получить список возможных начислений
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = ReceiptType::where('depends',  $request->type)->pluck('id');;
        $models = MeteringDevice::whereIn('type_id', $type)->where('enable', 1)->get();
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
        if ($request->id){
           $rate = MeteringDevice::find($request->id);
           $rate->type_id = $request->type_id;
           $rate->name = $request->name;
           $rate->enable = $request->enable;
           $rate->description = $request->description;
           $rate->save();
           if ($request->rate) {
               $r = new Rate();
               $r->device_id = $rate->id;
               $r->ratio_a = $request->rate['ratio_a'];
               $r->ratio_b = $request->rate['ratio_b'];
               $r->description = $request->rate['description'];
               $r->save();
           }
           return json_encode(['status'=>true, 'data'=>$rate]);
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
