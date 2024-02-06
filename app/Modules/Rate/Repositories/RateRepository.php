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

    public static function for_instrument_reading(InstrumentReadingModel $reading)
    {
        $device = $reading->metering_device;
        $rate_type = $device->rate_type;
        $rate_type->setCurrentDate($reading->date);
        $rate = $rate_type->currentRate;
        return $rate;
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