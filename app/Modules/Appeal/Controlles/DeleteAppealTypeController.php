<?php

namespace App\Modules\Appeal\Controlles;

use App\Http\Controllers\Controller;
use App\Modules\Appeal\Models\AppealTypeModel;

/**
 *
 * Удалить тип обращений
 *
 */
class DeleteAppealTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('ability:superAdmin,appeal-setting');
    }

    public function __invoke(AppealTypeModel $appealType)
    {
        if ($appealType->appeals->count() > 0) {
            return response(['errors' => 'Нельзя удалить тип если у него есть обращения'], 450);
        }
        if ($appealType->logAndDelete('Удаление типа обращений')) {
            return ['status' => true];
        }
        return response(['errors' => 'Ошибка удаления типа обращения'], 450);
    }
}
