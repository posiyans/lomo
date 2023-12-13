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


    public function find($find)
    {
        if ($find) {
            $uids = (new FindOwnerUserRepository($find))->uids();
            $this->query->whereIn('uid', $uids);
        }
        return $this;
    }


    /**
     * @param $uid
     * @return OwnerUserModel
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function byUid($uid)
    {
        return $this->query->where('uid', $uid)->firstOrFail();
    }

    public function run()
    {
        return $this->query->get();
    }

    public function paginate($limit)
    {
        return $this->query->orderBy('sort')->paginate($limit);
    }

}