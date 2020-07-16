<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptType extends Model
{
    //
    //depends
    // 0 - фиксированная сумма (наверно)
    // 1 - расчитывается от плащади участка
    // 2 - расчитывается  по показания

    public function MeteringDevice()
    {
        return $this->hasMany(MeteringDevice::class, 'type_id', 'id');
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
}
