<?php

namespace App\Modules\Comment\Repositories;

use App\Modules\Comment\Models\CommentModel;
use App\Modules\User\Models\UserModel;

class CommentRepository
{

    private $query;

    public function __construct()
    {
        $this->query = CommentModel::query();
    }

    /**
     * @throws \Exception
     */
    public function byType($type)
    {
        if ($type) {
            $this->query->where('commentable_type', CommentTypeRepository::getClassName($type));
        }
        return $this;
    }


    public function byUid($uid)
    {
        $this->query->where('uid', $uid);
        return $this;
    }

    public function one()
    {
        $model = $this->query->first();
        if ($model) {
            return $model;
        }
        throw new \Exception('Комментарий не найден');
    }

    public function sortBy($column, $direction)
    {
        $this->query->orderBy($column, $direction);
        return $this;
    }

    public function forUser(UserModel $user)
    {
        $this->query->where('user_id', $user->id);
        return $this;
    }

    public function paginate($limit = 20)
    {
        return $this->query->paginate($limit);
    }

}