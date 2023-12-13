<?php

namespace App\Modules\Stead\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stead\Repositories\SteadRepository;
use App\Modules\Stead\Validators\UpdateSteadOwnerProportionValidator;


class UpdateSteadOwnerProportionController extends Controller
{

    public function __invoke(UpdateSteadOwnerProportionValidator $request)
    {
        try {
            $stead = (new SteadRepository())->byId($request->stead_id);
            $stead->owners()->syncWithPivotValues($request->owner_uid, ['proportion' => $request->proportion], false);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
