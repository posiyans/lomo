<?php

namespace App\Modules\MeteringDevice\Actions;

use App\Modules\MeteringDevice\Models\MeteringDeviceModel;

/**
 * Обновить данных прибора учета
 */
class UpdateMeteringDeviceAction
{
    protected $device;

    public function __construct(MeteringDeviceModel $device)
    {
        $this->device = $device;
    }

    public function field($field, $value): UpdateMeteringDeviceAction
    {
        if (in_array($field, $this->device->getFillable())) {
            $this->device->$field = $value;
        } elseif (in_array($field, $this->device->getOptions())) {
            $options = $this->device->options;
            $options[$field] = $value;
            $this->device->options = $options;
        }
        return $this;
    }

    /**
     * @return MeteringDeviceModel
     * @throws \Exception
     */
    public function run()
    {
        if ($this->device->logAndSave('Обновление данных прибора')) {
            return $this->device;
        }
        throw new \Exception('Ошибка обновления данных прибора');
    }


}
