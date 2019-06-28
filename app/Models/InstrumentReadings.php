<?php
namespace App\Models;

use App\MyModel;

/**
 * Модель для показаний
 */
class InstrumentReadings extends MyModel
{
    //

    public function MeteringDevice()
    {
        return $this->hasOne(MeteringDevice::class, 'type_id', 'id');
    }




}