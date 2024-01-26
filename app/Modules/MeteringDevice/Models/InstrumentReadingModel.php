<?php

namespace App\Modules\MeteringDevice\Models;

use App\Models\MyModel;
use App\Modules\Rate\Models\RateModel;
use App\Modules\Receipt\Models\DeviceRegisterModel;

/**
 * Модель для показаний
 *
 * @property int $id
 * @property int $stead_id
 * @property int $device_id
 * @property string|null $instrument_serial
 * @property string $value
 * @property int|null $payment_id
 * @property int|null $invoice_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Modules\Receipt\Models\DeviceRegisterModel|null $deviceRegister
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadingModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadingModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadingModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadingModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadingModel whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadingModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadingModel whereInstrumentSerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadingModel whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadingModel wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadingModel whereSteadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadingModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadingModel whereValue($value)
 * @mixin \Eloquent
 */
class InstrumentReadingModel extends MyModel
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'metering_device_id',
        'value'
    ];

    protected $casts = [
        'value' => 'decimal:2',
    ];


    public function device_register()
    {
        return $this->hasOne(MeteringDeviceModel::class, 'id', 'metering_device_id');
    }

    /**
     * вернуть название типа и подтип устройства
     * @return mixed
     */
    public function deviceTypeName()
    {
        return $this->deviceRegister->getTypeName();
    }

    /**
     * получить единицы измерения
     */
    public function getUnit()
    {
        return $this->deviceRegister->MeteringDevice->receiptType->options['unit_name'];
    }

    public function getPrice()
    {
        $price = RateModel::query()
            ->where('device_id', $this->deviceRegister->type_id)
            ->where('created_at', '<', $this->created_at)
            ->orderBy('created_at', 'desc')
            ->first();
        return $price->ratio_a;
    }

    /**
     * получить модель придыдущих показаний
     * todo переделать !!
     *
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public function getPreviousReadingsModel()
    {
        $item = self::query()
            ->where('device_id', $this->device_id)
            ->where('created_at', '<=', $this->created_at)
            ->where('id', '<', $this->id)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($item) {
            return $item;
        }
        $item = DeviceRegisterModel::find($this->device_id);
        return $item;
    }


    /**
     * получить придыдущее показание
     *
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public function getPreviousReadings()
    {
        $item = self::query()
            ->where('device_id', $this->device_id)
            ->where('created_at', '<=', $this->created_at)
            ->where('id', '<', $this->id)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($item) {
            return $item->value;
        }
        $item = DeviceRegisterModel::find($this->device_id);
        return $item->initial_data;
    }

    /**
     * получить придыдущее показание
     *
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public function getPreviousInvoiceReadings()
    {
        $item = self::query()
            ->where('device_id', $this->device_id)
            ->where('created_at', '<=', $this->created_at)
            ->where('id', '<', $this->id)
            ->whereNotNull('invoice_id')
            ->orderBy('created_at', 'desc')
            ->first();
        if ($item) {
            return $item->value;
        }
        $item = DeviceRegisterModel::find($this->device_id);
        return $item->initial_data;
    }


    /**
     * проверить значение показания на то что они больше предыдущих
     *
     * @return bool
     */
    public function checkForLatestData()
    {
        $item = self::query()
            ->where('device_id', $this->device_id)
            ->where('value', '>', $this->value)
            ->first();
        if ($item) {
            return false;
        }
        $item = self::query()
            ->where('device_id', $this->device_id)
            ->where('value', '<', $this->value)
            ->first();
        if ($item) {
            return true;
        }
        $item = DeviceRegisterModel::query()
            ->where('id', $this->device_id)
            ->first();
        if ($item && $item->initial_data < $this->value) {
            return true;
        }
        return false;
    }


}
