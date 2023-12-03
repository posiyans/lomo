<?php

namespace App\Modules\Appeal\Controlles;

use App\Http\Controllers\Controller;
use App\Modules\Appeal\Repositories\AppealTypeRepository;
use App\Modules\Appeal\Resources\AppealTypeResource;
use Illuminate\Http\Request;

/**
 *
 * получить список типов обращений
 *
 */
class GetAppealTypeController extends Controller
{

    public function __construct()
    {
//        $this->middleware('ability:superAdmin,appeal-show|appeal-edit');
    }

    public function __invoke(Request $request)
    {
        $appeals = (new AppealTypeRepository())->all();
        return AppealTypeResource::collection($appeals);
    }
}
