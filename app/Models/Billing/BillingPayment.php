<?php

namespace App\Models\Billing;

use App\Models\Receipt\InstrumentReadings;
use App\Models\Log;
use App\Models\Stead;
use App\MyModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BillingPayment extends MyModel
{
    //
    protected $casts = [
        'raw_data' => 'array',
        'history' => 'array',
        'price' => 'float',
        'error' => 'boolean'
    ];

    public function instrumentReadings()
    {
      return $this->hasMany(InstrumentReadings::class, 'payment_id', 'id');
    }

    public function stead()
    {
        return $this->hasOne(Stead::class, 'id', 'stead_id');
    }

    public function steadObject(array $attributes = [])
    {
        $data = [];
        if ($this->stead) {
            if (count($attributes) == 0) {
                $attributes = $this->stead->getAttributes();
            }
            foreach ($attributes as $item) {
                if(isset($this->stead[$item])) {
                  $data[$item] = $this->stead[$item];
                }
            }
        }
        return $data;
    }

    /**
     * добавить лог, историю и сохранить
     *
     * @return bool|void
     */
    public function save(array $options = [])
    {
        $original_model = $this->getOriginal();
        $history = $this->history;
        $log = Log::addLog($this, $original_model, 'Изменение', $this->stead_id);
        if ($log) {
            $time = date('Y-m-d H:i:s');
            $history[] = ['date' => $time, 'user_id' => Auth::user()->id, 'data' => $log->value];
            $this->history = $history;
        }
        if (parent::save($options)) {
            return true;
        }
        return false;
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
            $payment->discription = '';
            $payment->payment_type = 1;
            $payment->raw_data = $item;
            $payment->user_id = Auth::user()->id;
            $payment->parseType();
            $payment->parseStead();
            if ($payment->checkNoDublicate()) {
                if ($payment->save()) {
                    return $payment;
                }
            } else {
               $payment->dubl = true;
               return $payment;
            }
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
    public function setMeterReading($data)
    {
        if ($data) {
            foreach ($data as $item) {
                $meter = InstrumentReadings::firstOrNew([
                    'payment_id' => $this->id,
                    'device_id' =>  $item['device']
                ]);
                $meter->stead_id = $this->stead_id;
                $meter->value = (int)$item['value'];
                $meter->created_at = $this->payment_date;
                if ($item['value']) {
                    $meter->logAndSave('Показания из платежки');
                } else {
                    if ($meter->id) {
                        $meter->delete();
                    }
                }
            }
//            BillingInvoice::createInvoiceCommunalForPayment($this);
        }
    }

    /**
     *удалить показания счетчиков по этому платежу
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
                $this->stead_id = $stead->id;
                if ($stead->number != mb_strtolower($data[$col])) {
                    $this->error = true;
                }
            }
        return $this;
    }


    public function parseType($col = 7)
    {
        $data = $this->raw_data;
        $type_1 = ['энер', 'свет',  'эл', 'эн.' ,'ээ','квт', 'счетчик', 'показан'];
        $type_2 = ['взнос', 'членск', 'целев', 'мусор' , 'земля', 'налог', 'ежегодный', 'чл.вз.', 'отходы'];
        $type = false;
        $str = mb_strtolower($data[$col]);
        foreach ($type_2 as $item) {
            if (stristr($str, $item) && !$type) {
                $type = 2;
            }
        }
        foreach ($type_1 as $item) {
            if (stristr($str, $item) && !$type) {
                $type = 1;
            }
        }

        if ($type) {
            $this->type = $type;
        } else {
            $this->error = true;
        }
        return $this;
    }
}
