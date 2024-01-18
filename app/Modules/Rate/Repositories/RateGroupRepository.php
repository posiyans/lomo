<?php

namespace App\Modules\Rate\Repositories;

use App\Modules\Rate\Models\RateGroupModel;

class RateGroupRepository
{

    private $query;

    public function __construct()
    {
        $this->query = RateGroupModel::query();
    }

    /**
     * @param $id
     * @return RateGroupModel
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function byId($id)
    {
        return $this->query->where('id', $id)->firstOrFail();
    }

    /**
     * @return \App\Models\MyModel[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @deprecated
     *
     */
    public function run()
    {
        return $this->get();
    }

    public function paginate($limit)
    {
        return $this->query->orderBy('id')->paginate($limit);
    }


    public function get()
    {
        return $this->query->get();
    }
}