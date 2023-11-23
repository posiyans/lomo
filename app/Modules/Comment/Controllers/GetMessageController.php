<?php

namespace App\Modules\Comment\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Comment\Repositories\GetCommentsForObject;
use App\Modules\Comment\Repositories\GetObjectByTypeAndUid;
use App\Modules\Comment\Resources\CommentResource;
use Illuminate\Http\Request;

/**
 * получить комментарии для обЪекта (тип и uid объекта)
 *
 */
class GetMessageController extends Controller
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
        $type = $request->get('type');
        $uid = $request->get('uid');
        $model = (new GetObjectByTypeAndUid($type, $uid))->run();
        $comments = (new GetCommentsForObject($model))->run();
        $result = CommentResource::collection($comments);
        return $result;
    }


}
