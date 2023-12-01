<?php

namespace App\Modules\Receipt\Models;

use App\Models\MyModel;

/*
 * Классификация квитанций
 *
 */

/**
 * App\Modules\Receipt\Models\ReceiptTypeModels
 *
 * @property int $id
 * @property string|null $name
 * @property bool $auto_invoice автоматическое выставление счета
 * @property array|null $options опции для участка
 * @property int $depends
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $payment_period период оплаты
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Receipt\Models\MeteringDevice> $MeteringDevice
 * @property-read int|null $metering_device_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptTypeModels newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptTypeModels newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptTypeModels query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptTypeModels whereAutoInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptTypeModels whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptTypeModels whereDepends($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptTypeModels whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptTypeModels whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptTypeModels whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptTypeModels wherePaymentPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptTypeModels whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ReceiptTypeModels extends MyModel
{
    //типы квитанций
    //depends рассчитывать
    // 0 - фиксированная сумма (наверно)
    // 1 - расчитывается от плащади участка
    // 2 - расчитывается  по показания

    protected $casts = [
        'options' => 'array',
        'auto_invoice' => 'boolean'
    ];

    protected $fillable = [
        'auto_invoice',
        'depends',
        'name',
        'options',
        'payment_period',
    ];

    public function MeteringDevice()
    {
        return $this->hasMany(MeteringDevice::class, 'type_id', 'id')->where('enable', 1);
    }


    public function saveInstrumentReadings($stead, $request)
    {
        if ($this->depends == 2) {
            foreach ($this->MeteringDevice as $MeteringDevice) {
                if (isset($request->device[$MeteringDevice->id])) {
                    $val = (float)str_replace(',', '.', $request->device[$MeteringDevice->id]);
                    if ($val > 0) {
                        $indication = new InstrumentReadingModel;
                        $indication->stead_id = $stead->id;
                        $indication->device_id = $MeteringDevice->id;
                        $indication->value = $val;
                        $indication->save();
                    }
                }
            }
            return true;
        }
        return false;
    }

    public function getCash($stead_id)
    {
        $cash = 0;
        foreach ($this->MeteringDevice as $MeteringDevice) {
            $MeteringDevice->rateNow();
            $cash += $MeteringDevice->getTicket($stead_id);
        }
        return $cash;
    }


    /**
     * проверить относится ли подгрупа к данной группе
     *
     * @param $id
     */
    public function checkForType($id)
    {
        return MeteringDevice::where('type_id', $this->id)->where('id', $id)->first();
    }

    public static function getReceiptTypeIds()
    {
        return self::pluck('id');
    }
}
