<?php

namespace App\Modules\MeteringDevice\Actions;

use App\Modules\MeteringDevice\Models\InstrumentReadingModel;

/**
 * Удалить показания прибора учета
 */
class DeleteInstrumentReadingAction
{
    protected $reading;

    public function __construct(InstrumentReadingModel $reading)
    {
        $this->reading = $reading;
    }


    /**
     * @return InstrumentReadingModel
     * @throws \Exception
     */
    public function run()
    {
        $device_id = $this->reading->metering_device_id;
        $date_start = $this->reading->date;
        if ($this->reading->logAndDelete('Удаление показаний прибора учета')) {
            (new CheckDataInInstrumentReadingAction())
                ->for_device($device_id)
                ->after_date($date_start)
                ->run();
            return true;
        }
        throw new \Exception('Ошибка добавления показаний пибора учета');
    }

}
