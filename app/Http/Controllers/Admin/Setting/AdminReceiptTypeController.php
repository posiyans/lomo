<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Setting\AdminReceiptTypesResource;
use App\Modules\Receipt\Models\ReceiptTypeModels;
use Illuminate\Http\Request;

class AdminReceiptTypeController extends Controller
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
        $items = ReceiptTypeModels::all();
//        $data = A;
        return ['status' => true, 'data' => AdminReceiptTypesResource::collection($items)];
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
        if ($request->name) {
            $item = new ReceiptTypeModels();
            $item->name = $request->name;
            if ($item->logAndSave('Создание')) {
                return ['status' => true, 'data' => $item];
            }
        }
        return ['status' => false, 'data' => 'Ошибка создания'];
    }

    /**
     * todo не то что должно быть !!!
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $type = ReceiptTypeModels::find($id);
        if ($type) {
            $items = $type->MeteringDevice;
            return ['status' => true, 'data' => $items];
        }
        return ['status' => false];
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
        $item = ReceiptTypeModels::find($id);
        if ($item) {
            $item->fill($request->all());
            if ($item->logAndSave('Изменение')) {
                return ['status' => true, 'data' => $item];
            }
        }
        return ['status' => false, 'data' => 'Упс'];
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
