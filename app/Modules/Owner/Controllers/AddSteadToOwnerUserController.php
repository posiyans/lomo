<?php

namespace App\Modules\Owner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Owner\Actions\AddSteadToOwnerAction;
use App\Modules\Owner\Repositories\OwnerUserRepository;
use App\Modules\Owner\Validators\AddSteadToOwnerUserValidator;
use App\Modules\Stead\Repositories\SteadRepository;

/**
 * Добавить участок собственнику
 */
class AddSteadToOwnerUserController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,owner-edit');
    }

    public function __invoke(AddSteadToOwnerUserValidator $request)
    {
        try {
            $owner = (new OwnerUserRepository())->byUid($request->owner_uid);
            $stead = (new SteadRepository())->byId($request->stead_id);
            $res = (new AddSteadToOwnerAction($stead, $owner))->proportion($request->proportion ?? 100)->run();
            return response(['status' => true, 'data' => $res]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
