<?php

namespace App\Modules\Comment\Classes;

use App\Modules\Comment\Models\CommentModel;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class CreateCommentClass
{
    private $comment;

    public function __construct($model)
    {
        $this->comment = new CommentModel();
        $this->comment->commentable_type = get_class($model);
        $this->comment->commentable_uid = $model->uid;
        $this->comment->user_id = Auth::id() ?? null;
        $this->comment->uid = Uuid::uuid4();
    }

    public function message($text)
    {
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