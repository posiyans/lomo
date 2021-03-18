<?php

namespace App\Http\Controllers\Admin\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Owner\AdminOwnerResource;
use App\Http\Resources\Admin\Owner\AdminOwnerListResource;
use App\Models\Owner\OwnerUserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class OwnerResourceController extends Controller
{
//    protected $query;

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-to-personal');
//        $this->query = BillingInvoice::query();
    }


    /**
     * получить список собственников
     *
     * @param Request $request
     */
    public function index(Request $request)
    {

        $owner = new GetOwnerListController($request);
        return [
            'status' => $owner->status,
            'data' => AdminOwnerListResource::collection($owner->rezult),
            'total' => $owner->total,
            'offset' => $owner->offset,
            'error' => $owner->error
        ];

//
//        $data = Cache::remember('allOwnerList', 600 ,function () {
//            $data = [];
//            $owners = OwnerUserModel::all();
//            foreach ($owners as $owner) {
//                $data[$owner->fullName()] = $owner;
//            }
//            ksort($data);
//            return array_values($data);
//        });
//        $total = count($data);
        $rez = $this->paginate($data, $request->page, $request->limit);
//
//        return ['status' => true, 'total' => $total, 'data' => AdminOwnerListResource::collection($rez)];
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
        // todo проверить на разрешение писать
        $owner = new CreateOwnerController($request->post('user', []));
        if (!$owner->error) {
            return ['status' => true, 'data' => $owner->getOwnerId()];
        }
        return ['stats' => true, 'error' => $owner->error_message];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $owner = OwnerUserModel::find($id);
        return  ['status' =>true, 'data' =>new AdminOwnerResource($owner)];
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
        $owner = OwnerUserModel::find($id);
        $fields = $request->post('fields', false);
        if ($owner && $fields && is_array($fields)) {
            foreach ($fields as $key=>$value) {
                if (isset($owner->fields[$key])) {
                    if ($owner->setValue($key, $value)) {
                        return ['status' => true];
                    }

                }
            }
        }
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
