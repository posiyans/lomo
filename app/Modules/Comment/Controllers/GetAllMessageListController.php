<?php

namespace App\Modules\Comment\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Comment\Repositories\CommentRepository;
use App\Modules\Comment\Resources\CommentResource;
use App\Modules\User\Repositories\GetUserByUidRepository;
use Illuminate\Http\Request;

/**
 * получить все комментарии в системе
 *
 */
class GetAllMessageListController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,comment-delete');
    }

    public function __invoke(Request $request)
    {
        try {
            $limit = $request->get('limit', 20);
            $sort_by = $request->get('sortBy', 'updated_at');
            $direction = $request->boolean('descending', false) ? 'asc' : 'desc';
            $user_uid = $request->get('user_uid');
            $query = (new CommentRepository())->sortBy($sort_by, $direction);
            if ($user_uid) {
                $user = (new GetUserByUidRepository($user_uid))->run();
                $query->forUser($user);
            }
            $comments = $query->paginate($limit);
            return CommentResource::collection($comments);
        } catch (\Exception $e) {
            response(['errors' => $e->getMessage()], 450);
        }
    }


}
