<?php

namespace App\Modules\Rate\Repositories;

use App\Modules\MeteringDevice\Models\InstrumentReadingModel;
use App\Modules\Rate\Models\RateModel;

class RateRepository
{

    private $query;

    public function __construct()
    {
        $this->query = RateModel::query();
    }

    public function for_instrument_reading(InstrumentReadingModel $reading)
    {
        $device = $reading->metering_device;
        $rate_type_id = $device->rate_type_id;
        $price = $this->query
            ->where('rate_type_id', $rate_type_id)
            ->where('date_start', '<', $reading->date)
            ->orderBy('created_at', 'desc')
            ->first();
        return $price;
    }


    public function paginate($limit)
    {
        return $this->paginate($limit);
    }


    public function get()
    {
        return $this->query->get();
    }
}