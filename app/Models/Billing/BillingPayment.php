<?php

namespace App\Models\Billing;

use App\Models\InstrumentReadings;
use App\Models\Stead;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BillingPayment extends Model
{
    //
    protected $casts = [
        'raw_data' => 'array',
        'history' => 'array',
        'price'=> 'float',
    ];

    public function stead()
    {
        return $this->hasOne(Stead::class, 'id', 'stead_id');
    }

    public function steadNumber()
    {
        if ($this->stead) {
            return $this->stead->number;
        }
        return '';
    }

    /**
     * добавить историю и сохранить
     *
     * @return bool|void
     */
    public function save(array $options = [])
    {
        $history = $this->history;
        $history[] = [
            'stead_id'=> $this->stead_id,
            'discription'=> $this->discription,
            'type'=> $this->type,
            'price'=> $this->price,
            'payment_date'=> $this->payment_date,
            'reestr_id'=> $this->reestr_id,
            'payment_type'=> $this->payment_type,
            'user_id'=> $this->user_id,
            'invoice_id'=> $this->invoice_id,
            'date'=> time(),
        ];
        $this->history = $history;
        return parent::save($options);
    }

    public static function smashTheReestr(BillingBankReestr $reestr)
    {
        try {
            $data = $reestr->data;
            $status = true;
            foreach ($data['data'] as $item) {
                $temp_date = explode('-', $item['val0']);
                $temp_time = explode('-', $item['val1']);
                $payment_data = $temp_date[2] . '-' . $temp_date[1] . '-' . $temp_date[0] . ' ' . $temp_time[0] . ':' . $temp_time[1] . ':' . $temp_time[2];
                $payment = new self;
                $payment->stead_id = $item['stead']['id'];
                $payment->discription = $item['val7'];
                $payment->type = $item['type'];
                $payment->transaction = $item['val4'];
                $payment->price = (float)$item['val8'];
                $payment->payment_date = $payment_data;
                $payment->reestr_id = $reestr->id;
                $payment->payment_type = 1;
                $payment->raw_data = $item;
                $payment->user_id = Auth::user()->id;
//                dump($payment);
//                dump($payment->checkNoDublicate());
                if ($payment->checkNoDublicate()) {
                    if (!$payment->save()) {
                        $status = false;
                    }
                }
            }
            return $status;
        } catch (\Exception $e) {
            echo $e;
            return false;
        }
        return true;
    }

    public function checkNoDublicate()
    {
        $find = BillingPayment::query()
            ->where('payment_date', $this->payment_date)
            ->where('price', $this->price)
            ->where('transaction', $this->transaction)
            ->first();
//        dump($this->payment_date);
//        dump($find);
        if ($find){
            return false;
        } else {
            return true;
        }

    }

    /**
     * получить счета для участка
     *
     * @param $stead_id
     * @param false $type
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getPaymentForStead($stead_id, $type = false)
    {
        $query = BillingPayment::query()->where('stead_id', $stead_id);
        if ($type) {
            $query->where('type', $type);
        }
        $invoices  = $query->orderBy('created_at')->get();
        return $invoices;
    }


    /**
     * установить показания счетчиков по этому платежу
     */
    public function setMeterReading()
    {
        if (isset($this->raw_data['meterReading1']) && !empty($this->raw_data['meterReading1']) && is_numeric($this->raw_data['meterReading1'])) {
            $meter = InstrumentReadings::firstOrNew([
//                'stead_id' => $this->stead_id,
                'payment_id' => $this->id,
                'device_id' =>  1
            ]);
            $meter->stead_id = $this->stead_id;
            $meter->value = $this->raw_data['meterReading1'];
            $meter->created_at = $this->payment_date;
            $meter->save();
        }
        if (isset($this->raw_data['meterReading2']) && !empty($this->raw_data['meterReading2']) && is_numeric($this->raw_data['meterReading2'])) {
            $meter = InstrumentReadings::firstOrNew([
//                'stead_id' => $this->stead_id,
                'payment_id' => $this->id,
                'device_id' => 2
            ]);
            $meter->stead_id = $this->stead_id;
            $meter->value = $this->raw_data['meterReading2'];
            $meter->created_at = $this->payment_date;
            $meter->save();
        }
    }

    /**
     *удалить показания счетчиков по этомк платежу
     */
    public function deleteMeterReading()
    {
       $items = InstrumentReadings::query()->where(['payment_id' => $this->id])->get();
       if ($items) {
           foreach ($items as $item) {
               $item->delete();
           }
       }
    }
}
