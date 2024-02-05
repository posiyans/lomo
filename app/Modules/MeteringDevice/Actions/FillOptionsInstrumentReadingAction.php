<?php

namespace App\Modules\MeteringDevice\Actions;

use App\Modules\MeteringDevice\Models\InstrumentReadingModel;
use App\Modules\MeteringDevice\Repositories\PreviousReadingsModelRepository;
use App\Modules\Rate\Repositories\RateRepository;

/**
 * Заполнить значения в поле options
 */
class FillOptionsInstrumentReadingAction
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
        $this->fillOptions();
        if ($this->reading->logAndSave('Обновление данных')) {
            return $this->reading;
        }
        throw new \Exception('Обновление данных');
    }


    private function fillOptions()
    {
        $options = $this->reading->options;
        $options['previous_value'] = (new PreviousReadingsModelRepository($this->reading))->value();
        $options['delta'] = (new PreviousReadingsModelRepository($this->reading))->delta();
        $options['rate'] = RateRepository::for_instrument_reading($this->reading);
        $options['cost'] = $options['delta'] * $options['rate']->ratio_a;
        $this->reading->options = $options;
    }

}
