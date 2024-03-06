<?php

namespace App\Modules\Appeal\Controlles;

use App\Http\Controllers\Controller;
use App\Modules\Appeal\Models\AppealModel;
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
        $user = Auth::user();
        if ($appeal->user_id == $user->id || $user->ability('superAdmin', ['appeal-edit'])) {
            $appeal->status = 'close';
            $appeal->date_close = now();
            $appeal->close_user_id = Auth::id();
            if ($appeal->logAndSave('Закрытие обращения')) {
                return ['status' => true, 'data' => $appeal];
            }
            return response(['errors' => $appeal->errors], 450);
        }
        return response(['errors' => 'Ошибка доступа'], 405);
    }
}
