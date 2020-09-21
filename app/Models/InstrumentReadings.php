<?php
namespace App\Models;

use App\MyModel;

/**
 * Модель для показаний
 */
class InstrumentReadings extends MyModel
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stead_id', 'payment_id', 'device_id'
    ];

    public function MeteringDevice()
    {
        return $this->hasOne(MeteringDevice::class, 'type_id', 'id');
    }




}
