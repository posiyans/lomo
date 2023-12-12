<?php

namespace App\Modules\Comment\Actions;

use App\Modules\Comment\Classes\ObsceneCensorRus;
use App\Modules\Comment\Interfaces\CommentedInterface;
use App\Modules\Comment\Models\CommentModel;
use App\Modules\User\Repositories\GetUserByUidRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class CreateCommentAction
{
    private $comment;

    public function __construct(CommentedInterface $model)
    {
        $uid_name = $model->uid();
        $this->comment = new CommentModel();
        $this->comment->commentable_type = get_class($model);
        $this->comment->commentable_uid = $model->$uid_name;
        $this->comment->user_id = Auth::id() ?? null;
        $this->comment->uid = Uuid::uuid4();
    }

    public function message($text)
    {
        if (!ObsceneCensorRus::isAllowed($text)) {
            Log::error($text);
            $user = (new GetUserByUidRepository($this->comment->user_id))->run();
            (new SendCensorNotificationAction($user, $text))->run();
        }
        ObsceneCensorRus::filterText($text);
//        $text = str_replace('*', '', $text);
        $this->comment->message = $text;
        return $this;
    }

    public function options($array)
    {
        $this->comment->options = $array;
        return $this;
    }

    public function uid($uid)
    {
        $this->comment->uid = $uid;
        return $this;
    }

    public function run()
    {
        if ($this->comment->logAndSave('Создание комментария')) {
            return $this->comment;
        }
        throw new \Exception($this->comment->errors);
    }


}