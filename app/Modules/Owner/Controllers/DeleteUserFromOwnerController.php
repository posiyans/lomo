<?php

namespace App\Modules\Owner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Owner\Actions\DeleteUserFromOwnerAction;
use App\Modules\Owner\Repositories\OwnerUserRepository;
use App\Modules\Owner\Validators\DeleteUserFromOwnerValidator;

/**
 * Открепить пользователя от собственника
 */
class DeleteUserFromOwnerController extends Controller
{

    public function __invoke(DeleteUserFromOwnerValidator $request)
    {
        try {
            $owner = (new OwnerUserRepository())->byUid($request->owner_uid);
            (new DeleteUserFromOwnerAction($owner))->run();
            return response(['status' => true]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
