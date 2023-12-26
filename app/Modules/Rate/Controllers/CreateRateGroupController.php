<?php

namespace App\Modules\Rate\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rate\Actions\CreateRateTypeAction;
use App\Modules\Rate\Validators\CreateRateGroupValidator;

/**
 * Добавить группу тарифов
 */
class CreateRateGroupController extends Controller
{

    public function __invoke(CreateRateGroupValidator $request)
    {
        try {
            $rate_group = (new CreateRateTypeAction($request->name))->run();
            return response(['status' => true, 'data' => $rate_group]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
