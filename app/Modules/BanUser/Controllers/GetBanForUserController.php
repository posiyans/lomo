<?php

namespace App\Modules\BanUser\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\BanUser\Repositories\BanUserRepository;
use App\Modules\BanUser\Resources\BanUserResource;
use App\Modules\BanUser\Validators\GetBanForUserValidator;
use App\Modules\User\Repositories\GetUserByUidRepository;

/**
 * Получить бан для пользователя
 *
 */
class GetBanForUserController extends Controller
{

    public function __invoke(GetBanForUserValidator $request)
    {
        try {
            $user = (new GetUserByUidRepository($request->user_uid))->run();
            $withTrashed = $request->with_trashed;
            if (!$request->isAdmin()) {
                $withTrashed = false;
            }
            $query = (new BanUserRepository())->forUser($user);
            if ($withTrashed == 'true') {
                $query->withTrashed();
            }
            $bans = $query->sortBy('id', 'DESC')->paginate($request->limit);
            return BanUserResource::collection($bans);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 410);
        }
    }


}
