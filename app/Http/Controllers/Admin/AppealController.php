<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Appeal\Modules\AppealModel;
use App\Modules\Appeal\Resources\AppealResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppealController extends Controller
{

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,appeal-show|appeal-edit');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = AppealModel::query();
        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        $appeal = $query->paginate($request->limit);
        return AppealResource::collection($appeal);
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
    public function show(AppealModel $apppel)
    {
        return $apppel;
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
        $user = Auth::user();
        if ($user->ability('superAdmin', 'edit-appels')) {
            $model = AppealModel::find($id);
            $data = $request->post('appeal');
            if ($data) {
                if (isset($data['new_message']) && !empty($data['new_message'])) {
                    $model->addMessage($data['new_message'], $user->id);
                }
                $model->fill($data);
                if ($model->save()) {
                    // todo залогировать!!!
                    return json_encode($model);
                }
            }
        }
        return false;
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
