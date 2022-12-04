<?php

namespace App\Modules\Avatar\Controllers;

use App\Http\Resources\AppealResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ConrtollerResource;
use App\Models\AppealModel;
use App\Models\Article\ArticleModel;
use App\Models\Article\CategoryModel;
use App\Modules\Avatar\Repositories\GetAvatarForUserRepository;
use App\Modules\File\Classes\CheckAccessToFileClass;
use App\Modules\File\Classes\GetPathForHashClass;
use App\Modules\File\Classes\SaveFileForObjectClass;
use App\Modules\File\Classes\TempDirectoryPathClass;
use App\Modules\File\Repositories\GetFileByUidRepository;
use App\Modules\File\Repositories\GetObjectByType;
use App\Modules\File\Repositories\GetObjectForFileRepository;
use App\Modules\File\Resources\FileResource;
use App\Modules\User\Repositories\GetUserByUidRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

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
            return response()->download($path, $avatar->name);
        } catch (\Exception $e) {
            return response()->download(__DIR__ . '/img.png', 'avatar.png');
        }
    }


}
