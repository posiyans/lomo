<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\AdminSteadResource;
use App\Http\Resources\AppealResource;
use App\Http\Resources\ConrtollerResource;
use App\Http\Resources\SteadResource;
use App\Models\AppealModel;
use App\Models\Stead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminSteadController extends Controller
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
        $query = Stead::query();
        if(isset($request->title) && !empty($request->title)){
            $query->where('number', 'LIKE', '%'.$request->title.'%');
        }
        $steads = $query->paginate($request->limit);
        return SteadResource::collection($steads);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $stead = Stead::find($id);
        $data = new AdminSteadResource($stead);

        return ['status' => true, 'data' => $data->resolve()];
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
    public function update($id, Request $request)
    {
        // todo добавить проверку на права изменения!
        $stead = Stead::find($id);
        if ($stead && $stead->updateStead($request->all())) {
            return json_encode(['status'=>true, 'data'=>$stead]);
        }
//        // добавить комментарий к участку
//        if (isset($request->type) &&  $request->type == 'addNote' && isset($request->note) && !empty($request->note)){
//            $stead = Stead::addNote($id, $request->note);
//            if ($stead){
//                return json_encode(['status'=>true, 'data'=>$stead]);
//            }
//        }
//        if (isset($request->type) &&  $request->type == 'update' && isset($request->model) && !empty($request->model)){
//            $stead = Stead::updateStead($id, $request->model);
//            if ($stead){
//                return json_encode(['status'=>true, 'data'=>$stead]);
//            }
//        }
        return json_encode(['status'=>false, 'data'=>'Упс']);
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
