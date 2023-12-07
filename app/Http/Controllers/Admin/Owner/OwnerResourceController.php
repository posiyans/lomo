<?php

namespace App\Http\Controllers\Admin\Owner;

use App\Http\Controllers\Admin\AbstractAdminController;
use App\Http\Controllers\Admin\Owner\Classes\DeleteOwner;
use App\Http\Controllers\Admin\Owner\Repository\GetOwnerListRepository;
use App\Http\Controllers\Admin\Owner\Repository\GetOwnerRepository;
use App\Http\Controllers\Admin\Owner\Request\OwnerListRequest;
use App\Http\Controllers\Admin\Owner\Resource\OwnreListXlsxFileResource;
use App\Http\Resources\Admin\Owner\AdminOwnerResource;
use App\Models\Owner\OwnerUserModel;
use App\Modules\Owner\Resources\OwnerUserAndSteadsResource;
use Illuminate\Http\Request;


class OwnerResourceController extends AbstractAdminController
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
        $title = $request->get('title', false);
        $owner = new GetOwnerListRepository($title);
        return [
            'status' => $owner->status,
            'data' => OwnerUserAndSteadsResource::collection($owner->rezult),
            'meta' => [
                'total' => $owner->total,
                'offset' => $owner->offset,
            ],
            'error' => $owner->error
        ];
//        $rez = $this->paginate($data);
    }


    /**
     * todo вынести в отдельный клонтроллер
     *
     * @param OwnerListRequest $request
     * @return \Illuminate\Http\Response
     */
    public function ownerListXlsx(OwnerListRequest $request)
    {
        $title = $request->get('title', false);
        $owners = new GetOwnerListRepository($title, true);
        return response((new OwnreListXlsxFileResource())->render($owners->rezult));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::user()->hasPermission('write-personal-data')) {
            $owner = new CreateOwnerController($request->post('user', []));
            if (!$owner->error) {
                return ['status' => true, 'data' => $owner->getOwnerId()];
            }
            return ['stats' => true, 'error' => $owner->error_message];
        }
        return ['status' => false];
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $owner = OwnerUserModel::find($id);
        if ($owner) {
            return ['status' => true, 'data' => new AdminOwnerResource($owner)];
        }
        return ['status' => false];
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
        if (\Auth::user()->hasPermission('write-personal-data')) {
            $owner = OwnerUserModel::find($id);
            $fields = $request->post('fields', false);
            if ($owner && $fields && is_array($fields)) {
                foreach ($fields as $key => $value) {
                    if (isset($owner->fields[$key])) {
                        if ($owner->setValue($key, $value)) {
                            return ['status' => true];
                        }
                    }
                }
            }
        }
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
                $owner = (new GetOwnerRepository())->findById($id);
                $status = (new DeleteOwner())->deleteOwner($owner);
                if ($status) {
                    return ['status' => true];
                }
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
        }
        return ['status' => false, 'error' => 'Не прав на удаление'];
    }

}
