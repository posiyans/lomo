<?php

namespace App\Http\Controllers\Admin\Owner;

use App\Http\Controllers\Admin\AbstractAdminController;
use App\Http\Controllers\Admin\Owner\Classes\DeleteOwnerClass;
use App\Http\Controllers\Admin\Owner\Classes\DeleteOwnerUserStead;
use App\Http\Controllers\Admin\Owner\Repository\OwnerUserSteadRepository;
use App\Http\Controllers\Admin\Owner\Request\OwnerListRequest;
use Illuminate\Http\Request;


class OwnerUserSteadResourceController extends AbstractAdminController
{
//    protected $query;

    /**z
     * проверка на суперадмин или на доступ к персоналке
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('ability:superAdmin,access-to-personal');
    }


    /**
     * получить список собственников
     *
     * @param Request $request
     */
    public function index(OwnerListRequest $request)
    {
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
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
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
    public function update($id, Request $request)
    {
    }

    /**
     * Удалить собственника
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Auth::user()->hasPermission('write-personal-data')) {
            try {
                $ownerStead = (new OwnerUserSteadRepository())->findById($id);
                $status = (new DeleteOwnerUserStead())->deleteRelations($ownerStead);
                if ($status) {
                    return ['status' => true];
                }
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return ['status' => false, 'error' => $e->getMessage()];
            }
        }
        return ['status' => false];
    }

}
