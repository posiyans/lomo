<?php
namespace App\Models\Receipt;

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
        return $this->hasOne(MeteringDevice::class, 'id', 'device_id');
    }

    public function getPrice()
    {
        $price = Rate::query()
            ->where('device_id', $this->device_id)
            ->where('created_at', '<', $this->created_at)
            ->orderBy('created_at', 'desc')
            ->first();
        return $price->ratio_a;
    }




}
