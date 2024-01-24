<?php

namespace App\Modules\Receipt\Models;

use App\Models\MyModel;
use App\Models\Receipt\id;
use App\Modules\MeteringDevice\Models\InstrumentReadingModel;
use App\Modules\MeteringDevice\Models\MeteringDevice;

/**
 * App\Modules\Receipt\Models\DeviceRegisterModel
 *
 * @property int $id
 * @property int $type_id тип прибора
 * @property int $stead_id к какому участку отностся
 * @property int $initial_data начальные показания
 * @property string $serial_number серийный номер прибора
 * @property string $device_brand модель прибора
 * @property string|null $installation_date дата установки
 * @property string|null $verification_date дата до следующей поверки прибора
 * @property string|null $descriptions коментарий
 * @property bool $active запрашивать показания
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Modules\MeteringDevice\Models\MeteringDevice|null $MeteringDevice
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceRegisterModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceRegisterModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceRegisterModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceRegisterModel whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceRegisterModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceRegisterModel whereDescriptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceRegisterModel whereDeviceBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceRegisterModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceRegisterModel whereInitialData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceRegisterModel whereInstallationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceRegisterModel whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceRegisterModel whereSteadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceRegisterModel whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceRegisterModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceRegisterModel whereVerificationDate($value)
 * @mixin \Eloquent
 */
class DeviceRegisterModel extends MyModel
{
//    protected $table = 'device_register';
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
        'installation_date' => null,
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
        return $this->hasMany(InstrumentReadingModel::class, 'device_id', 'id')->orderBy('created_at', 'desc');
    }

    /**
     * получить последние показани по прибору
     *
     * @return mixed
     */
    public function getLastReading()
    {
        return InstrumentReadingModel::where('device_id', $this->id)->orderBy('created_at', 'desc')->first();
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
        return InstrumentReadingModel::where('device_id', $this->id)->whereNotNull('invoice_id')->orderBy('created_at', 'desc')->first();
    }


    /**
     * добавить показания по прибору
     *
     * @param $value -- показание
     */
    public function addReading($value)
    {
        $reading = new InstrumentReadingModel();
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
     * @param $type - тип прибора
     * @param array $options массив с остальными необязательными параметрами
     * [
     *      initial_data - начальные показания
     *      serial_number - integer    серийный номер прибора
     *      device_brand - text    модель прибора
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
        if ($device->logAndSave('Добавлен прибор', $device->stead_id)) {
            return $device;
        }
        return false;
    }


}
