<?php

namespace App\Modules\File\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\File\Classes\CheckAccessToFileClass;
use App\Modules\File\Classes\GetPathForHashClass;
use App\Modules\File\Repositories\GetFileByUidRepository;
use App\Modules\User\Repositories\GetUserByUidRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * получить фаил
 *
 */
class GetFileController extends Controller
{

//    public function __construct()
//    {
//        $this->middleware('auth:sanctum');
//    }

    public function index(Request $request)
    {
//        dd(Auth::user());
        try {
//            $user_uid = $request->session()->get('user_uid');
            $user_uid = Auth::user() ? Auth::user()->uid : false;
            $uid = $request->uid;
            $file = (new GetFileByUidRepository($uid))->run();
            try {
                if ($user_uid) {
                    $user = (new GetUserByUidRepository($user_uid))->run();
                }
            } catch (\Exception $e) {
                $user = null;
            }
            // автор файла имеет доступ всегда
//            if (!$user || $user->id != $file->user_id) {
            (new CheckAccessToFileClass())->file($file)->forUser($user)->read();
//            }

            $path = (new GetPathForHashClass($file->hash))->run();
            return Storage::download($path, $file->name);
//            return response(['errors' => 'Ошибка доступа'], 403);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 451);
        }
    }


}
