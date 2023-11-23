<?php

namespace App\Modules\Stead\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stead\Repositories\SteadRepository;
use App\Modules\Stead\Resources\SteadResource;
use App\Modules\Stead\Validators\GetListOfOwnerSteadValidator;
use App\Modules\User\Repositories\GetUserByUidRepository;

/**
 * Получить список участков для собственника
 */
class GetListOfOwnerSteadController extends Controller
{

    public function __invoke(GetListOfOwnerSteadValidator $request)
    {
        $user_uid = $request->get('user_uid');
        $user = (new GetUserByUidRepository($user_uid))->run();
        $steads = (new SteadRepository())->forUser($user)->run();
        return SteadResource::collection($steads);
    }


}
