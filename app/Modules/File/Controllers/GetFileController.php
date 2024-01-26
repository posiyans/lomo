<?php

namespace App\Modules\File\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\File\Classes\CheckAccessToFileClass;
use App\Modules\File\Classes\GetPathForHashClass;
use App\Modules\File\Repositories\GetFileByUidRepository;
use App\Modules\File\Repositories\PreviewFileRepository;
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
//        try {
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
        (new CheckAccessToFileClass())->file($file)->forUser($user)->read();

        $path = (new GetPathForHashClass($file->hash))->run();
        $file_name = $file->name;
        if ($request->preview) {
            try {
                $width = $request->get('width', 800);
                $path = (new PreviewFileRepository($path))->width($width)->path();
                $file_name .= '.jpg';
            } catch (\Exception $e) {
                $path_parts = pathinfo($file->name);
                $extension = $path_parts['extension'];
                if (file_exists(__DIR__ . '/../IconFile/' . $extension . '.png')) {
                    return response()->download(__DIR__ . '/../IconFile/' . $extension . '.png');
                }
            }
        }
        return Storage::download($path, $file_name);
//        } catch (\Exception $e) {
//            return response(['errors' => $e->getMessage()], 451);
//        }
    }


}
