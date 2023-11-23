<?php

namespace App\Modules\Comment\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Comment\Classes\DeleteCommentClass;
use App\Modules\Comment\Repositories\CommentRepository;
use Auth;
use Illuminate\Http\Request;

/**
 * Удалить коментарий
 *
 */
class DeleteMessageController extends Controller
{


    /**
     * проверка на суперадмин или на доступ в админ панель
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $uid = $request->get('uid');
            $model = (new CommentRepository())->byUid($uid)->one();
            if ($model->user_id === $user->id || $user->ability('superAdmin', 'comment-delete')) {
                (new DeleteCommentClass($model))->run();
                return true;
            }
            return response(['errors' => 'Ошибка доступа'], 403);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 410);
        }
    }


}
