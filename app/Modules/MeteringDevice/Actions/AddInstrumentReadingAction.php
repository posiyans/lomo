<?php

namespace App\Modules\MeteringDevice\Actions;

use App\Modules\MeteringDevice\Models\InstrumentReadingModel;
use App\Modules\MeteringDevice\Models\MeteringDeviceModel;

/**
 * Добавить прибора учета
 */
class AddInstrumentReadingAction
{
    protected $reading;

    public function __construct(MeteringDeviceModel $device)
    {
        $this->reading = new InstrumentReadingModel();
        $this->reading->metering_device_id = $device->id;
    }

    public function value($value)
    {
        $this->reading->value = $value;
        return $this;
    }

    public function date($date)
    {
        $this->reading->date = $date;
        return $this;
    }

    /**
     * @return InstrumentReadingModel
     * @throws \Exception
     */
    public function run()
    {
        if ($this->reading->logAndSave('Добавление показаний прибора учета')) {
            return $this->reading;
        }
        throw new \Exception('Ошибка добавления показаний пибора учета');
    }


}
