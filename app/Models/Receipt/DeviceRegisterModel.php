<?php

namespace App\Models\Receipt;

use App\MyModel;

class DeviceRegisterModel extends MyModel
{
    protected $table = 'device_register';
    protected $casts = [
        'active' => 'boolean'
    ];
    protected $fillable = [
        'type_id',
        'stead_id',
        'initial_data',
        'serial_number',
        'device_brand',
        'installation_date',
        'verification_date',
        'descriptions',
    ];

    public function MeteringDevice()
    {
        return $this->hasOne(MeteringDevice::class, 'id', 'type_id');
    }

    public function getTypeName()
    {

        return [$this->MeteringDevice->receiptType->name, $this->MeteringDevice->name];
    }

    public function getReadings()
    {
        return $this->hasMany(InstrumentReadings::class, 'device_id', 'id')->orderBy('created_at', 'desc');
    }

    public function getLastReading()
    {
        return  InstrumentReadings::where('device_id', $this->id)->orderBy('created_at', 'desc')-> first();
    }


}
