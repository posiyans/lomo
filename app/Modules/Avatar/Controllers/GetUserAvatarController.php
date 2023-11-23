<?php

namespace App\Modules\Avatar\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Avatar\Classes\GravatarClass;
use App\Modules\Avatar\Repositories\GetAvatarForUserRepository;
use App\Modules\File\Classes\GetPathForHashClass;
use App\Modules\User\Repositories\GetUserByUidRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * получить фаил
 *
 */
class GetUserAvatarController extends Controller
{


    /**
     * проверка на суперадмин или на доступ в админ панель
     */
    public function __construct()
    {
//        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    public function index(Request $request)
    {
        try {
            $uid = $request->uid;
            $user = (new GetUserByUidRepository($uid))->run();
            $avatar = (new GetAvatarForUserRepository($user))->run();
            $path = (new GetPathForHashClass($avatar->hash))->run();
            return Storage::download($path, $avatar->name);
        } catch (\Exception $e) {
            if ($user) {
                $label = mb_strtolower(trim(mb_substr($user->name, 0, 2)));
                $generator = new GravatarClass();
                $avatar = $generator->string($label)->toPng();
                return response($avatar, 200, [
                    'Content-Type' => 'image/png',
                    'Content-Disposition' => 'attachment; filename="avatar.png"',
                ]);
            }
            return response()->download(__DIR__ . '/img.png', 'avatar.png');
        }
    }


}
