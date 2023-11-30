<?php

namespace App\Modules\Owner\Repositories;

use App\Modules\Owner\Models\OwnerUserModel;

class OwnerUserRepository
{

    private $query;

    public function __construct()
    {
        $this->query = OwnerUserModel::query();
    }


    public function byUid($uid)
    {
        return $this->query->where('uid', $uid)->firstOrFail();
    }

    public function paginate($limit)
    {
        return $this->query->paginate($limit);
    }

}