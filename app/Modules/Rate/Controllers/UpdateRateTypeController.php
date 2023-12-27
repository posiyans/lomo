<?php

namespace App\Modules\Rate\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rate\Actions\UpdateRateTypeAction;
use App\Modules\Rate\Models\RateTypeModel;
use App\Modules\Rate\Validators\UpdateRateTypeValidator;

/**
 * Обновить описание тарифа
 */
class UpdateRateTypeController extends Controller
{

    public function __invoke(RateTypeModel $typeModel, UpdateRateTypeValidator $request)
    {
        try {
            return (new UpdateRateTypeAction($typeModel, $request->validated()))->run();
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
