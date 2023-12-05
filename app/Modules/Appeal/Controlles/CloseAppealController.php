<?php

namespace App\Modules\Appeal\Controlles;

use App\Http\Controllers\Controller;
use App\Modules\Appeal\Modules\AppealModel;
use Illuminate\Support\Facades\Auth;

/**
 *
 * получить обращение
 *
 */
class CloseAppealController extends Controller
{

    public function __invoke(AppealModel $appeal)
    {
        if (!$appeal->commentWrite(Auth::user())) {
            return response(['errors' => 'Ошибка доступа'], 405);
        }
        $appeal->status = 'close';
        $appeal->date_close = now();
        $appeal->close_user_id = Auth::id();
        if ($appeal->logAndSave('Закрытие обращения')) {
            return ['status' => true, 'data' => $appeal];
        }
        return response(['errors' => $appeal->errors], 450);
    }
}
