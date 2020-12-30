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



    /**
     * разнести платежку по счетам садоводов
     *
     * @param BillingBankReestr $reestr
     * @return bool
     */
    public static function createPlayment(array $item)
    {
        try {
            $date = date_create_from_format('d-m-Y H-i-s', $item[0]. ' ' .$item[1]);
            $payment_data = date_format($date, 'Y-m-d H:i:s');
            $payment = new self;
            $payment->price = (float)str_replace(',', '.', $item[8]);
            $payment->transaction = $item[4];
            $payment->payment_date = $payment_data;
//                    $payment->stead_id = $item['stead']['id'];
                $payment->discription = $item[7];
//                    $payment->type = $item['type'];
//                    $payment->reestr_id = $reestr->id;
                $payment->payment_type = 1;
                $payment->raw_data = $item;
                $payment->user_id = Auth::user()->id;
//                dump($payment);
            if ($payment->checkNoDublicate()) {
                if (!$payment->save()) {
                    return false;
                }
            } else {
               $payment->dubl = true;
               return $payment;
            }
            return $payment;
        } catch (\Exception $e) {
            return false;
        }
        return false;
    }


    /**
     * разнести платежку по счетам садоводов
     *
     * @param BillingBankReestr $reestr
     * @return bool
     */
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
                $payment->price = (float)str_replace(',', '.', $item['val8']);
                $payment->transaction = $item['val4'];
                $payment->payment_date = $payment_data;
                if ($payment->checkNoDublicate()) {
                    $payment->stead_id = $item['stead']['id'];
                    $payment->discription = $item['val7'];
                    $payment->type = $item['type'];
                    $payment->reestr_id = $reestr->id;
                    $payment->payment_type = 1;
                    $payment->raw_data = $item;
                    $payment->user_id = Auth::user()->id;
//                dump($payment);
//                dump($payment->checkNoDublicate());
//                if ($payment->checkNoDublicate()) {
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


    /**попробывать получить номер участка
     *
     *
     * @param int $col
     * @return $this
     */
    public function parseStead($col = 5)
    {
        $d =

        $data = $this->raw_data;
        $str = mb_strtolower($data[$col]);
        $str = str_replace('-', '/', $str);
        $str = str_replace('-', '/', $str);
        $str = str_replace(',', '/', $str);
        $str = str_replace('участок', '', $str);
        $str = str_replace('№', '', $str);
        $str = str_replace(' ', '', $str);
        $stead = Stead::query()->where('number', 'like', "%{$str}%")->first();
        if(!$stead) {
            $str = str_replace('Л сч 502 10линия', '502', $data['val'.$col]);
            $str = str_replace('Л сч502 10линия', '502', $str);
            $str = str_replace('288, 289', '288', $str);
            $str = str_replace('526/525', '525/526', $str);
            $stead = Stead::query()->where('number', 'like', "%{$str}%")->first();
        }
            if ($stead) {
                $data['stead'] = ['id' => $stead->id, 'number' => $stead->number];
                if ($stead->number == mb_strtolower($data[$col])) {
                    $this->stead_id = $stead->id;
                } else {
                    $data['error'] = true;
                    $error[] = $data;
                }
            } else {
                $data['stead'] = ['id' => '', 'number' => '-'];
                $data['error'] = true;
                $error[] = $data;
            }

        $d['data'] = array_merge($error, $ok);
        $this->data  = $d;
        return $this;
    }
}
