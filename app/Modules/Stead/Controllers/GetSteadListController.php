<?php

namespace App\Modules\Stead\Controllers;

use App\Http\Resources\AppealResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ConrtollerResource;
use App\Models\AppealModel;
use App\Models\Article\ArticleModel;
use App\Models\Article\CategoryModel;
use App\Modules\Avatar\Repositories\GetAvatarForUserRepository;
use App\Modules\Comment\Classes\CreateCommentClass;
use App\Modules\Comment\Repositories\GetCommentsForObject;
use App\Modules\Comment\Repositories\GetObjectByTypeAndUid;
use App\Modules\Comment\Resources\CommentResource;
use App\Modules\File\Classes\CheckAccessToFileClass;
use App\Modules\File\Classes\GetPathForHashClass;
use App\Modules\File\Classes\SaveFileForObjectClass;
use App\Modules\File\Classes\TempDirectoryPathClass;
use App\Modules\File\Repositories\GetFileByUidRepository;
use App\Modules\File\Repositories\GetObjectByType;
use App\Modules\File\Repositories\GetObjectForFileRepository;
use App\Modules\File\Resources\FileResource;
use App\Modules\Stead\Repositories\GetSteadRepository;
use App\Modules\Stead\Resources\SteadResource;
use App\Modules\User\Repositories\GetUserByUidRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class GetSteadListController extends Controller
{


    /**
     * проверка на суперадмин или на доступ в админ панель
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $find = $request->get('find', '');
        $id = $request->get('id', '');
        if ($request->page) {
            $steads = (new GetSteadRepository())->findByNumber($find)->findById($id)->paginate();
        } else {
            $steads = (new GetSteadRepository())->findByNumber($find)->findById($id)->run();
        }
        return SteadResource::collection($steads);
    }


}
