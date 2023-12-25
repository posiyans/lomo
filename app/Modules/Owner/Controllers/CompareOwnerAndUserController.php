<?php

namespace App\Modules\Owner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Owner\Actions\CompareOwnerAndUserAction;
use App\Modules\Owner\Repositories\OwnerUserRepository;
use App\Modules\Owner\Validators\CompareOwnerAndUserValidator;
use App\Modules\User\Repositories\GetUserByUidRepository;

/**
 * Прикрепить к собственнику пользователя сайта
 */
class CompareOwnerAndUserController extends Controller
{

    public function __invoke(CompareOwnerAndUserValidator $request)
    {
        try {
            $owner = (new OwnerUserRepository())->byUid($request->owner_uid);
            $user = (new GetUserByUidRepository($request->user_uid))->run();
            if ($user->owner) {
                return response(['status' => false, 'errors' => 'Ошибка. Пользователь уже валидирован как собственник'], 450);
            }
            (new CompareOwnerAndUserAction($owner, $user))->run();
            return response(['status' => true]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
