<?php
namespace App\Modules\Comment\Repositories;

use App\Modules\Comment\Models\CommentModel;

class GetCommentsForObject
{

    private $query;

    public function __construct($model)
    {
        $this->query = CommentModel::query();
        $this->query->where('commentable_type', get_class($model));
        $this->query->where('commentable_uid', $model->uid);
    }

    public function run()
    {
        $this->query->orderBy('created_at');
        $models = $this->query->get();
        return $models;
    }
}