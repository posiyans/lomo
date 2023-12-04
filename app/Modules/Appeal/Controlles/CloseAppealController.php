<?php

namespace App\Modules\Appeal\Controlles;

use App\Http\Controllers\Controller;
use App\Modules\Appeal\Modules\AppealModel;
use Auth;

/**
 *
 * получить обращение
 *
 */
class CloseAppealController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,appeal-edit');
    }

    public function __invoke(AppealModel $appeal)
    {
        $appeal->status = 'close';
        $appeal->date_close = now();
        $appeal->close_user_id = Auth::id();
        if ($appeal->logAndSave('Закрытие обращения')) {
            return ['status' => true, 'data' => $appeal];
        }
        return response(['errors' => $appeal->errors], 450);
    }
}
