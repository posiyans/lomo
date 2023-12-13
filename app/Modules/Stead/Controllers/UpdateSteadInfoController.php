<?php

namespace App\Modules\Stead\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stead\Actions\UpdateSteadFieldAction;
use App\Modules\Stead\Models\SteadModel;
use App\Modules\Stead\Validators\UpdateSteadInfoValidator;


class UpdateSteadInfoController extends Controller
{

    public function __invoke(SteadModel $stead, UpdateSteadInfoValidator $request)
    {
        try {
            (new UpdateSteadFieldAction($stead))->field($request->field, $request->value)->run();
            return $stead;
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }

//        $stead = (new GetSteadByIdRepository($id))->run();

//        $user = \Auth::user();
//        if ($request->full && $user && $user->ability('superAdmin', ['stead-show', 'stead-edit'])) {
//            return new AdminSteadResource($stead);
//        }
//        return new SteadResource($stead);
    }


}
