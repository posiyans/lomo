<?php

namespace App\Models\Receipt;

use App\MyModel;

/*
 * Классификация квитанций
 *
 */
class ReceiptType extends MyModel
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


    public function saveInstrumentReadings($stead, $request){
        if ($this->depends == 2){
            foreach ($this->MeteringDevice as $MeteringDevice) {
                if (isset($request->device[$MeteringDevice->id])) {
                    $val = (float)str_replace(',', '.', $request->device[$MeteringDevice->id]);
                    if ($val > 0) {
                        $indication = new InstrumentReadings;
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

    public function getCash($stead_id){
        $cash = 0;
        foreach ($this->MeteringDevice as $MeteringDevice) {
            $MeteringDevice->rateNow();
            $cash += $MeteringDevice->getTicket($stead_id);
        }
        return $cash;
    }


    public static function getReceiptTypeIds()
    {
        return self::pluck('id');
    }
}
