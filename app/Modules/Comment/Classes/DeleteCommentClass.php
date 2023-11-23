<?php

namespace App\Modules\Comment\Classes;

use App\Modules\Comment\Models\CommentModel;
use Illuminate\Support\Facades\Auth;

class DeleteCommentClass
{
    private $model;

    public function __construct(CommentModel $model)
    {
        $this->model = $model;
    }

    public function run()
    {
        if (Auth::user()) {
            $this->model->user_deletes_id = Auth::user()->id;
            $this->model->save();
        }
        if ($this->model->delete()) {
            return true;
        }
        throw new \Exception('Ошибка удаления');
    }


}