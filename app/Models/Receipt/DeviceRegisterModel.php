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
        'active'
    ];

    protected $default_values = [
        'initial_data' => 0,
        'serial_number' => 'auto_create',
        'device_brand' => 'auto_create',
        'installation_date'=> null,
        'verification_date' => null,
        'descriptions' => ''
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

    /**
     * получить последние показани по прибору
     *
     * @return mixed
     */
    public function getLastReading()
    {
        return  InstrumentReadings::where('device_id', $this->id)->orderBy('created_at', 'desc')->first();
    }

    /**
     * получить последние показани по прибору
     *
     * @return integer
     */
    public function getLastReadingValue()
    {
        if ($this->getLastReading()) {
            return $this->getLastReading()->value;
        }
        return $this->initial_data;
    }


    /**
     * получить последние показяния по которым выставлен счет
     * @return mixed
     */
    public function getLastInvoiceReading()
    {
        return  InstrumentReadings::where('device_id', $this->id)->whereNotNull('invoice_id')->orderBy('created_at', 'desc')-> first();
    }


    /**
     * добавить показания по прибору
     *
     * @param $value -- показание
     */
    public function addReading($value)
    {
        $reading = new InstrumentReadings();
        $reading->stead_id = $this->stead_id;
        $reading->device_id = $this->id;
        $reading->value = $value;
        if ($reading->logAndSave('Добавленый показания пользователем', $this->stead_id)) {
            return $reading;
        }
        return false;
    }


    /**
     * добавить прибор
     *
     * @param $stead id - участка
     * @param $type  - тип прибора
     * @param array $options массив с остальными необязательными параметрами
     * [
     *      initial_data - начальные показания
     *      serial_number - integer	серийный номер прибора
     *      device_brand - text	модель прибора
     *      installation_date - date дата установки
     *      verification_date - date дата до следующей поверки прибора
     *      descriptions - text коментарий
     *      active - boolean запрашивать показания
     * ]
     */
    public static function addDevice($stead, $type, $options = [])
    {
        $device = new self();
        $options = array_merge($device->default_values, $options);
        $device->fill($options);
        $device->stead_id = $stead;
        $device->type_id = $type;
        if ($device->logAndSave('Добавлен прибор', $device->stead_id ))
        {
            return $device;
        }
        return false;

    }


}
