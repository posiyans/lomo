<?php

namespace App\Modules\Rate\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rate\Actions\UpdateRateTypeAction;
use App\Modules\Rate\Models\RateTypeModel;
use App\Modules\Rate\Validators\CreateRateTypeValidator;

/**
 * Создать тип тарифа (например Электр. День или Электр. Ночь)
 */
class CreateRateTypeController extends Controller
{

    public function __invoke(CreateRateTypeValidator $request)
    {
        try {
            $typeModel = new RateTypeModel();
            $typeModel->rate_group_id = $request->rate_group_id;
            return (
            new UpdateRateTypeAction($typeModel, $request->validated())
            )->run();
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
