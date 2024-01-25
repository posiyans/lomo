<?php

namespace App\Modules\MeteringDevice\Actions;

use App\Modules\MeteringDevice\Models\MeteringDeviceModel;
use App\Modules\Rate\Models\RateTypeModel;
use App\Modules\Stead\Models\SteadModel;

/**
 * Добавить прибора учета
 */
class CreateMeteringDeviceAction
{
    protected $device;

    public function __construct(SteadModel|int $stead, RateTypeModel|int $rate_type)
    {
        $this->device = new MeteringDeviceModel();
        $this->device->stead_id = is_object($stead) ? $stead->id : $stead;
        $this->device->rate_type_id = is_object($rate_type) ? $rate_type->id : $rate_type;
    }

    public function fill($array): CreateMeteringDeviceAction
    {
        $this->device->fill($array);
        return $this;
    }

    public function options($array): CreateMeteringDeviceAction
    {
        $option_fields = $this->device->getOptions();
        $tmp = array_filter($array, function ($k) use ($option_fields) {
            return in_array($k, $option_fields);
        }, ARRAY_FILTER_USE_KEY);
        $this->device->options = $tmp;
        return $this;
    }

    /**
     * @return MeteringDeviceModel
     * @throws \Exception
     */
    public function run()
    {
        if ($this->device->logAndSave('Добавление прибора учета')) {
            return $this->device;
        }
        throw new \Exception('Ошибка добавления пибора учета');
    }


}
