<?php

namespace App\Modules\Rate\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rate\Actions\UpdateRateGroupAction;
use App\Modules\Rate\Models\RateGroupModel;
use App\Modules\Rate\Validators\UpdateRateGroupValidator;

/**
 * Обновить группу тарифов
 */
class UpdateRateGroupController extends Controller
{

    public function __invoke(RateGroupModel $groupModel, UpdateRateGroupValidator $request)
    {
        try {
            $options = $groupModel->options;
            $options['tag'] = $request->tag;
            $options['unit_name'] = $request->unit_name;
            $data = $request->only(['name', 'auto_invoice', 'payment_period', 'depends']);
            $data['options'] = $options;
            return (new UpdateRateGroupAction($groupModel, $data))->run();
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
