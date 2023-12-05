<?php

namespace App\Modules\Appeal\Controlles;

use App\Http\Controllers\Controller;
use App\Modules\Appeal\Modules\AppealModel;
use App\Modules\Appeal\Resources\AppealFullResource;
use Illuminate\Support\Facades\Auth;

/**
 *
 * получить обращение
 *
 */
class GetAppealController extends Controller
{

    public function __invoke(AppealModel $appeal)
    {
        if (!$appeal->commentRead(Auth::user())) {
            return response(['errors' => 'Ошибка доступа'], 405);
        }
        return ['appeal' => new AppealFullResource($appeal)];
    }
}
