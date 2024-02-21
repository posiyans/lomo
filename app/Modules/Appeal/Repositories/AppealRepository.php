<?php

namespace App\Modules\Appeal\Repositories;

use App\Modules\Appeal\Models\AppealModel;

class AppealRepository
{

    private $query;

    public function __construct()
    {
        $this->query = AppealModel::query();
    }

    public function find($find)
    {
        if ($find) {
            $this->query->where(function ($query) use ($find) {
                $query->where('title', 'like', '%' . $find . '%')
                    ->orWhere('text', 'like', '%' . $find . '%');
            });
        }
        return $this;
    }

    public function type($type)
    {
        if ($type) {
            $this->query->where('appeal_type_id', $type);
        }
        return $this;
    }

    public function status($status)
    {
        if ($status) {
            $this->query->where('status', $status);
        }
        return $this;
    }

    public function user($user)
    {
        if ($user) {
            $this->query->where('user_id', $user->id);
        }
        return $this;
    }

    public function sortBy($column = 'id', $direction = 'desc')
    {
        $this->query->orderBy($column, $direction);
        return $this;
    }

    public function paginate($limit)
    {
        return $this->query->paginate($limit);
    }

    public function all()
    {
        return $this->query->get();
    }


}