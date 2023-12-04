<?php

namespace App\Modules\Appeal\Controlles;

use App\Http\Controllers\Controller;
use App\Modules\Appeal\Modules\AppealModel;
use App\Modules\Appeal\Resources\AppealFullResource;

/**
 *
 * получить обращение
 *
 */
class GetAppealController extends Controller
{

    public function __construct()
    {
    }

    public function __invoke(AppealModel $appeal)
    {
        return ['appeal' => new AppealFullResource($appeal)];
//        return ['appeal' => $appeal];
    }
}
