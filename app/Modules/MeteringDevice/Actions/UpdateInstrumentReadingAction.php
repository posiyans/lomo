<?php

namespace App\Modules\MeteringDevice\Actions;

use App\Modules\MeteringDevice\Models\InstrumentReadingModel;

/**
 * Обновить показания прибора учета
 */
class UpdateInstrumentReadingAction
{
    protected $reading;

    public function __construct(InstrumentReadingModel $reading)
    {
        $this->reading = $reading;
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

    public function payment($payment)
    {
        if ($payment) {
            $this->reading->payment_id = $payment;
        }
        return $this;
    }

    /**
     * @return InstrumentReadingModel
     * @throws \Exception
     */
    public function run()
    {
        if ($this->reading->logAndSave('Изменение показаний прибора учета')) {
            return $this->reading;
        }
        throw new \Exception('Ошибка изменниея показаний пибора учета');
    }


}
