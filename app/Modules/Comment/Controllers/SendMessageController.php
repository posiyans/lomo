<?php

namespace App\Modules\Comment\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\BanUser\Actions\CheckUserBanAction;
use App\Modules\Comment\Classes\CreateCommentClass;
use App\Modules\Comment\Repositories\GetObjectByTypeAndUid;
use Illuminate\Http\Request;

/**
 * получить фаил
 *
 */
class SendMessageController extends Controller
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
            $type = $request->get('type');
            $uid = $request->get('uid');
            (new CheckUserBanAction($request->user()))
                ->type($request->type)
                ->typeUid($request->uid)
                ->run();
            $message = $request->get('message', null);
            $reply = $request->get('reply');
            $model = (new GetObjectByTypeAndUid($type, $uid))->run();
            // todo проверить права доступа на данную модель
            $opt = [
                'reply' => $reply
            ];
            $comment = (new CreateCommentClass($model))->message($message)->options($opt)->run();
            return $comment;
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }


}
