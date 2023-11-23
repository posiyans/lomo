<?php
namespace App\Models\Receipt;

use App\Models\MyModel;

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
 * @property-read \App\Models\Receipt\DeviceRegisterModel|null $deviceRegister
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadings query()
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadings whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadings whereInstrumentSerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadings whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadings wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadings whereSteadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadings whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstrumentReadings whereValue($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @mixin \Eloquent
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

    public function deviceRegister()
    {
        return $this->hasOne(DeviceRegisterModel::class, 'id', 'device_id');
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
        $price = Rate::query()
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
