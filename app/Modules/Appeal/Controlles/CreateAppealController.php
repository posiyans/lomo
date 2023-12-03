<?php

namespace App\Modules\Appeal\Controlles;

use App\Http\Controllers\Controller;
use App\Modules\Appeal\Actions\CreateAppealAction;
use App\Modules\Appeal\Resources\AppealResource;
use App\Modules\Appeal\Validators\CreateAppealValidator;

/**
 *
 * создать обращение
 *
 */
class CreateAppealController extends Controller
{

    public function __invoke(CreateAppealValidator $request)
    {
        $appeal = (new CreateAppealAction($request->validated()))->run();

        return new AppealResource($appeal);
    }
}
