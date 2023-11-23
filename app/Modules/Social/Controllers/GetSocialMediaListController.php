<?php

namespace App\Modules\Social\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Social\Repositories\GetSocialAccountForUser;
use App\Modules\User\Repositories\GetUserByUidRepository;
use Auth;
use Illuminate\Http\Request;

/**
 * Получить список социальных сетей пользователя
 *
 */
class GetSocialMediaListController extends Controller
{

    public function __invoke(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user->ability('superAdmin', ['user-edit', 'user-show'])) {
                if (($user_uid = $request->user_uid)) {
                    $user = (new GetUserByUidRepository($user_uid))->run();
                }
            }
            $social = (new GetSocialAccountForUser($user))->all()->map(function ($item, $key) {
                return [
                    'provider_name' => $item->provider_name,
                    'provider_id' => $item->provider_id
                ];
            });
            return response(['data' => $social]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 420);
        }
    }


}
