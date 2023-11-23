<?php

namespace App\Modules\BanUser\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\BanUser\Actions\CheckUserBanAction;
use Auth;
use Illuminate\Http\Request;

/**
 * Проверить возможность оставлять комментарии
 *
 */
class CheckUserBanController extends Controller
{


    /**
     *
     * Оставлять комментарии могут только авторизованные пользователи
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request)
    {
        // todo
        $user = Auth::user();
        try {
            (new CheckUserBanAction($user))
                ->type($request->type)
                ->typeUid($request->uid)
                ->run();
            return ['ban' => false];
        } catch (\Exception $e) {
            return [
                'ban' => true,
                'errorMessage' => $e->getMessage(),
            ];
        }
    }


}
