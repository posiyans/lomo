<?php

namespace App\Models\Receipt;


use App\MyModel;

/*
 * Модель приборов учета
 */
class MeteringDevice extends MyModel
{
    //

    protected $casts = [
        'enable' => 'bollean',
    ];
    public function receiptType()
    {
        return $this->hasOne(ReceiptType::class, 'id', 'type_id');
    }
    /**
     *получить последний тарифф
     */
    public function rateNow()
    {
        $rate = Rate::Where('device_id', $this->id)->orderBy('created_at', 'desc')->first();
        if ($rate) {
            $this->rate =  $rate;
        } else {
            $this->rate =  [
                'ratio_a' => 0,
                'ratio_b' => 0,
                'discription' => ''
                ];
        }
    }

    /**
     * получить n-ое показание прибора
     * @param $n
     * @return int
     */
    public function getIndication($stead_id, $n) {
        $indications = InstrumentReadings::where('device_id', $this->id)->where('stead_id', $stead_id)->orderBy('created_at', 'desc')->skip($n)->take(1)->get();
        return isset($indications[0]) ? $indications[0] : false;
    }

    /**
     * получить последние показание прибора
     * и занести его в атрибут LastIndication
     * @return int|mixed
     */
    public function getLastIndication($stead_id) {
        $this->LastIndication = $this->getIndication($stead_id, 0);
        return $this->LastIndication;
    }


    public function getTicket($stead_id, $n=0){
        $receiptType = $this->receiptType;
        $description = '';
        $this->rateNow();
        if ($receiptType->depends == 1){
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
            $temp = $this->getIndication($stead_id,$n + 1);
            $this->ValueOld = $temp ? $temp->value : 0;
            $this->rateNow();
            $this->cash = ($this->ValueNew - $this->ValueOld) * $this->rate->ratio_a + $this->ratio_b;
            if (($this->ValueNew - $this->valueOld) > 0 and $this->rate->ratio_a > 0) {
                $description .= '(' . $this->ValueNew . ' - ' . $this->ValueOld . ') * ' . $this->rate->ratio_a;
            }
        }
        if ($this->rate->ratio_b > 0){
            if ($description != ''){
                $description.= ' + ';
            }
            $description.= $this->rate->ratio_b;
        }
        if ($description != '') {
            $this->cash_description = $description.' = '.$this->cash;
        }else{
            $this->cash_description = false;
        }
        return $this->cash;

    }

}
