<?php

namespace App\Modules\Appeal\Controlles;

use App\Http\Controllers\Controller;
use App\Modules\Appeal\Models\AppealModel;
use App\Modules\Appeal\Validators\UpdateAppealValidator;

/**
 *
 * изменить обращение
 *
 */
class UpdateAppealController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,appeal-edit');
    }

    public function __invoke(AppealModel $appeal, UpdateAppealValidator $request)
    {
        if ($appeal->status == 'close') {
            return response(['errors' => 'Обращение закрыто'], 405);
        }
        $field = $request->field;
        $value = $request->value;
        $appeal->$field = $value;
        if ($appeal->logAndSave('Изменение обращения')) {
            return ['status' => true, 'data' => $appeal];
        }
        return response(['errors' => $appeal->errors], 450);
    }
}
