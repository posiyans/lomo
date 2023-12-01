<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Modules\Receipt\Models\MeteringDevice;
use App\Modules\Receipt\Models\RateModel;
use App\Modules\Receipt\Models\ReceiptTypeModels;
use Illuminate\Http\Request;

class RateController extends Controller
{
    /**
     * получить список возможных начислений
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = ReceiptTypeModels::where('id', $request->type)->pluck('id');;
        $models = MeteringDevice::whereIn('type_id', $type)->where('enable', 1)->get();
        foreach ($models as $model) {
            $model->rateNow();
        }
        return ['status' => true, 'data' => $models];
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->id) {
            $rate = MeteringDevice::find($request->id);
            $rate->type_id = $request->type_id;
            $rate->name = $request->name;
            $rate->enable = $request->enable;
            $rate->description = $request->description;
            $rate->save();
            if ($request->rate) {
                $r = new RateModel();
                $r->device_id = $rate->id;
                $r->ratio_a = $request->rate['ratio_a'];
                $r->ratio_b = $request->rate['ratio_b'];
                $r->description = $request->rate['description'];
                $r->save();
            }
            return json_encode(['status' => true, 'data' => $rate]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
