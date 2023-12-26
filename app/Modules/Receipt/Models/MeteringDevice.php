<?php

namespace App\Modules\Receipt\Models;


use App\Models\MyModel;
use App\Models\Stead;
use App\Modules\Rate\Models\RateModel;

/*
 * Модель приборов учета
 */

/**
 * App\Modules\Receipt\Models\MeteringDevice
 *
 * @deprecated
 *
 * @property int $id
 * @property string $type_id
 * @property string|null $name
 * @property string|null $description
 * @property bool|null $enable
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @property-read \App\Modules\Receipt\Models\ReceiptTypeModels|null $receiptType
 * @method static \Illuminate\Database\Eloquent\Builder|MeteringDevice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeteringDevice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeteringDevice query()
 * @method static \Illuminate\Database\Eloquent\Builder|MeteringDevice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeteringDevice whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeteringDevice whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeteringDevice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeteringDevice whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeteringDevice whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeteringDevice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MeteringDevice extends MyModel
{
    //

    protected $casts = [
        'enable' => 'boolean',
    ];

    public function receiptType()
    {
        return $this->hasOne(ReceiptTypeModels::class, 'id', 'type_id');
    }

    /**
     *получить последний тарифф
     */
    public function rateNow()
    {
        $rate = RateModel::Where('device_id', $this->id)->orderBy('created_at', 'desc')->first();
        if ($rate) {
            $this->rate = $rate;
        } else {
            $this->rate = [
                'ratio_a' => 0,
                'ratio_b' => 0,
                'description' => ''
            ];
        }
        return $this->rate;
    }

    /**
     * получить n-ое показание прибора
     *
     * @param $n
     * @return int
     * @deprecated
     */
    public function getIndication($stead_id, $n)
    {
        $indications = InstrumentReadingModel::where('device_id', $this->id)->where('stead_id', $stead_id)->orderBy('created_at', 'desc')->skip($n)->take(1)->get();
        return isset($indications[0]) ? $indications[0] : false;
    }

    /**
     * получить последние показание прибора
     * и занести его в атрибут LastIndication
     *
     * @return int|mixed
     * @deprecated
     */
    public function getLastIndication($stead_id)
    {
        $this->LastIndication = $this->getIndication($stead_id, 0);
        return $this->LastIndication;
    }

    public function getDeviceForStead($stead_id)
    {
        return DeviceRegisterModel::where('type_id', $this->id)->where('stead_id', $stead_id)->where('active', 1)->get();
    }

    /**
     * незнаю что
     *
     * @param $stead_id
     * @param int $n
     * @return float|int|mixed
     */
    public function getTicket($stead_id, $n = 0)
    {
        $receiptType = $this->receiptType;
        $description = '';
        $this->rateNow();
        if ($receiptType->depends == 1) {
            $stead = Stead::find($stead_id);
            if ($stead) {
                $size = $stead->size;
                $this->cash = $size * 0.01 * $this->rate->ratio_a + $this->rate->ratio_b;
                if ($size > 0 and $this->rate->ratio_a > 0) {
                    $description .= $size / 100 . ' * ' . $this->rate->ratio_a;
                }
            }
        }
        if ($receiptType->depends == 2) {
            $temp = $this->getIndication($stead_id, $n);
            $this->ValueNew = $temp ? $temp->value : 0;
            $temp = $this->getIndication($stead_id, $n + 1);
            $this->ValueOld = $temp ? $temp->value : 0;
            $this->rateNow();
            $this->cash = ($this->ValueNew - $this->ValueOld) * $this->rate->ratio_a + $this->ratio_b;
            if (($this->ValueNew - $this->valueOld) > 0 and $this->rate->ratio_a > 0) {
                $description .= '(' . $this->ValueNew . ' - ' . $this->ValueOld . ') * ' . $this->rate->ratio_a;
            }
        }
        if ($this->rate->ratio_b > 0) {
            if ($description != '') {
                $description .= ' + ';
            }
            $description .= $this->rate->ratio_b;
        }
        if ($description != '') {
            $this->cash_description = $description . ' = ' . $this->cash;
        } else {
            $this->cash_description = false;
        }
        return $this->cash;
    }

    public static function getDeviceIdForStead($type, $stead_id, $subtype = false)
    {
        if ($subtype) {
            $device = DeviceRegisterModel::where('stead_id', $stead_id)
                ->where('type_id', $subtype)
                ->pluck('id');
        } else {
            $metering_device = self::where('type_id', $type)->where('enable', 1)->pluck('id');
            $device = DeviceRegisterModel::where('stead_id', $stead_id)
                ->whereIn('type_id', $metering_device)
                ->pluck('id');
        }

        return $device;
    }

}
