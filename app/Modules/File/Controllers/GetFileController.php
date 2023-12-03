<?php

namespace App\Modules\File\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\File\Classes\CheckAccessToFileClass;
use App\Modules\File\Classes\GetPathForHashClass;
use App\Modules\File\Repositories\GetFileByUidRepository;
use App\Modules\User\Repositories\GetUserByUidRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * получить фаил
 *
 */
class GetFileController extends Controller
{


    public function index(Request $request)
    {
        try {
            $user_uid = $request->session()->get('user_uid');
            $uid = $request->uid;
            $file = (new GetFileByUidRepository($uid))->run();
            $access = (new CheckAccessToFileClass($file))->run();
            try {
                if (!$access && $user_uid) {
                    $user = (new GetUserByUidRepository($user_uid))->run();
                    $access = (new CheckAccessToFileClass($file))->forUser($user)->run();
                }
            } catch (\Exception $e) {
            }

            if ($access) {
//                dd($file->hash);
                $path = (new GetPathForHashClass($file->hash))->run();
                return Storage::download($path, $file->name);
            }
            return response(['errors' => 'Ошибка доступа'], 403);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 451);
        }
    }


}
