<?php

namespace App\Modules\Comment\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Comment\Repositories\GetCommentsForObject;
use App\Modules\Comment\Repositories\GetObjectByTypeAndUid;
use App\Modules\Comment\Resources\CommentResource;
use App\Modules\Comment\Validators\GetMessagesValidator;
use Illuminate\Support\Facades\Auth;

/**
 * получить комментарии для обЪекта (тип и uid объекта)
 *
 */
class GetMessagesController extends Controller
{


    public function index(GetMessagesValidator $request)
    {
        try {
            $model = (new GetObjectByTypeAndUid($request->type, $request->uid))->run();
            $user = Auth::user();
            if (!$model->commentRead($user)) {
                throw new \Exception('Ошибка доступа');
            }
            $comments = (new GetCommentsForObject($model))->run();
            $result = CommentResource::collection($comments);
            return $result;
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
