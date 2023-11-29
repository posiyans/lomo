<?php

namespace App\Modules\Owner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Owner\Actions\AddSteadToOwnerAction;
use App\Modules\Owner\Actions\CreateOwnerAction;
use App\Modules\Owner\Validators\CreateOwnerUserValidator;
use App\Modules\Stead\Repositories\GetSteadByIdRepository;
use Illuminate\Support\Facades\DB;

/**
 * Создать собственника
 */
class CreateOwnerUserController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,owner-edit');
    }

    public function __invoke(CreateOwnerUserValidator $request)
    {
        try {
            DB::beginTransaction();
            $owner = (new CreateOwnerAction($request->owner))->run();
            foreach ($request->steads as $stead_id) {
                $stead = (new GetSteadByIdRepository($stead_id))->run();
                (new AddSteadToOwnerAction($stead, $owner))->run();
            }
            DB::commit();
            return response(['data' => $owner]);
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return response(['errors' => $e->getMessage()], 450);
    }

}
