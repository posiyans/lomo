<?php

namespace App\Modules\MeteringDevice\Repositories;

use App\Modules\MeteringDevice\Models\InstrumentReadingModel;

class InstrumentReadingRepository
{

    private $query;

    public function __construct()
    {
        $this->query = InstrumentReadingModel::query();
    }


    public function forStead($steadId)
    {
        if ($steadId) {
            if (is_array($steadId)) {
                $this->query->whereHas('device_register', function ($query) use ($steadId) {
                    $query->whereIn('stead_id', $steadId);
                });
            } else {
                $this->query->whereHas('device_register', function ($query) use ($steadId) {
                    $query->where('stead_id', $steadId);
                });
            }
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