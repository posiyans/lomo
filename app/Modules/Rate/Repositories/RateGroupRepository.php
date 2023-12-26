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


    public function run()
    {
        return $this->query->get();
    }

    public function paginate($limit)
    {
        return $this->query->orderBy('id')->paginate($limit);
    }

}