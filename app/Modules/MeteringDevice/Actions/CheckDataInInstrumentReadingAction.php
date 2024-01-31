<?php

namespace App\Modules\MeteringDevice\Actions;

use App\Modules\MeteringDevice\Models\InstrumentReadingModel;
use App\Modules\MeteringDevice\Repositories\InstrumentReadingRepository;

/**
 * Исправить доп данные в выборке показаний
 *
 */
class CheckDataInInstrumentReadingAction
{
    private $readings;

    public function __construct()
    {
        $this->readings = new InstrumentReadingRepository();;
    }


    public function after_reading(InstrumentReadingModel $reading)
    {
        $this->for_device($reading->metering_device_id);
        $this->after_date($reading->date);
        return $this;
    }

    public function for_device($device_id)
    {
        if ($device_id) {
            $this->readings->for_device($device_id);
        }
        return $this;
    }

    public function after_date($date)
    {
        if ($date) {
            $this->readings->between_date($date, null);
        }
        return $this;
    }

    /**
     * @return InstrumentReadingModel
     * @throws \Exception
     */
    public function run()
    {
        $readings = $this->readings->get();
        foreach ($readings as $reading) {
            (new FillOptionsInstrumentReadingAction($reading))->run();
        }
    }


}
