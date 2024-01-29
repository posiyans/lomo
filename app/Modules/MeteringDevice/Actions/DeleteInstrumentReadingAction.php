<?php

namespace App\Modules\MeteringDevice\Actions;

use App\Modules\MeteringDevice\Models\InstrumentReadingModel;
use App\Modules\MeteringDevice\Repositories\InstrumentReadingRepository;

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
            $this->reFillOptions($device_id, $date_start);
            return true;
        }
        throw new \Exception('Ошибка добавления показаний пибора учета');
    }

    private function reFillOptions($device_id, $date_start)
    {
        $readings = (new InstrumentReadingRepository())
            ->for_device($device_id)
            ->between_date($date_start, null)
            ->get();
        foreach ($readings as $reading) {
            (new FillOptionsInstrumentReadingAction($reading))->run();
        }
    }


}
