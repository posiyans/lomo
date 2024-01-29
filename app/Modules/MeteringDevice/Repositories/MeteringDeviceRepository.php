<?php

namespace App\Modules\MeteringDevice\Repositories;

use App\Modules\MeteringDevice\Models\MeteringDeviceModel;

class MeteringDeviceRepository
{

    private $query;

    public function __construct()
    {
        $this->query = MeteringDeviceModel::query();
    }

    public function getById($id)
    {
        return $this->query->where('id', $id)->firstOrFail();
    }

    public function forStead($steadId)
    {
        if ($steadId) {
            $this->query->where('stead_id', $steadId);
        }
        return $this;
    }

    public function get()
    {
        return $this->query->get();
    }

    public function paginate($limit)
    {
        return $this->query->orderBy('sort')->paginate($limit);
    }

}