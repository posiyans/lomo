<?php

namespace App\Modules\Rate\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rate\Actions\UpdateRateAction;
use App\Modules\Rate\Models\RateTypeModel;
use App\Modules\Rate\Validators\UpdateRateValidator;

/**
 * Обновить тариф
 */
class UpdateRateController extends Controller
{

    public function __invoke(RateTypeModel $typeModel, UpdateRateValidator $request)
    {
        try {
            return (new UpdateRateAction($typeModel, $request->validated()))->run();
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
