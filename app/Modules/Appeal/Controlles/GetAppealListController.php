<?php

namespace App\Modules\Appeal\Controlles;

use App\Http\Controllers\Controller;
use App\Modules\Appeal\Repositories\AppealRepository;
use App\Modules\Appeal\Resources\AppealResource;
use App\Modules\Appeal\Validators\GetAppealListValidator;

/**
 *
 * получить список обращений
 *
 */
class GetAppealListController extends Controller
{

    public function __invoke(GetAppealListValidator $request)
    {
        $appeals = (new AppealRepository())
            ->user($request->getUser())
            ->type($request->type)
            ->find($request->find)
            ->status($request->status)
            ->paginate($request->limit);
        return AppealResource::collection($appeals);
    }
}
