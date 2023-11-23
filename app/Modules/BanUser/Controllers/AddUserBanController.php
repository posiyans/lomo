<?php

namespace App\Modules\BanUser\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\BanUser\Classes\AddUserBanClass;
use App\Modules\BanUser\Validators\AddUserBanValidator;
use App\Modules\User\Repositories\GetUserByUidRepository;

use function response;

/**
 *
 * Забанить пользователя
 *
 */
class AddUserBanController extends Controller
{

    public function __invoke(AddUserBanValidator $request)
    {
        try {
            $user = (new GetUserByUidRepository($request->user_uid))->run();
            (new AddUserBanClass($user))
                ->description($request->description)
                ->beforeTimes($request->getEndBanTime())
                ->setType($request->type)
                ->setTypeUid($request->uid)
                ->run();
            return ['status' => true];
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 410);
        }
    }


}
