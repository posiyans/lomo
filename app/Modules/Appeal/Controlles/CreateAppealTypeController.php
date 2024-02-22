<?php

namespace App\Modules\Appeal\Controlles;

use App\Http\Controllers\Controller;
use App\Modules\Appeal\Models\AppealTypeModel;
use App\Modules\Appeal\Resources\AppealTypeResource;
use App\Modules\Appeal\Validators\CreateAppealTypeValidator;

/**
 *
 * Создать тип обращений
 *
 */
class CreateAppealTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('ability:superAdmin,appeal-setting');
    }

    public function __invoke(AppealTypeModel $appealType, CreateAppealTypeValidator $request)
    {
        $appealType->fill($request->validated());
        if ($appealType->logAndSave('Создание типа обращений')) {
            return ['data' => new AppealTypeResource($appealType)];
        }
        return response(['errors' => 'Ошибка обновления типа обращения'], 450);
    }
}
