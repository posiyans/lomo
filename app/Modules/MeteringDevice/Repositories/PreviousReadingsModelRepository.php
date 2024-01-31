<?php

namespace App\Modules\MeteringDevice\Repositories;

use App\Modules\MeteringDevice\Models\InstrumentReadingModel;

/**
 * получить предыдущие показания по текущему прибору
 */
class PreviousReadingsModelRepository
{

    private $reading;

    public function __construct(InstrumentReadingModel $reading)
    {
        $this->reading = $reading;
    }


    public function get()
    {
        $device = $this->reading->metering_device;
        $reading = (new InstrumentReadingRepository())->for_device($device->id)
            ->where('date', '<=', $this->reading->date)
            ->where('id', '!=', $this->reading->id)
            ->orderBy('date', 'desc')
            ->first();
        return $reading;
    }

    public function value()
    {
        $reading = $this->get();
        if ($reading) {
            return $reading->value;
        } else {
            return $this->reading->metering_device->initial_data;
        }
    }

    public function delta()
    {
        return $this->reading->value - $this->value();
    }

}