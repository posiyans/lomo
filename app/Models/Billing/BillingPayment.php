<?php

namespace App\Models\Billing;

use App\Models\Receipt\DeviceRegisterModel;
use App\Models\Receipt\InstrumentReadings;
use App\Models\Log;
use App\Models\Receipt\MeteringDevice;
use App\Models\Receipt\ReceiptType;
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

    /**
     * получить участок который платил
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stead()
    {
        return $this->hasOne(Stead::class, 'id', 'stead_id');
    }

    public function getType()
    {
      return $this->hasOne(ReceiptType::class, 'id', 'type');
    }

    public function getDeviceReestrForPayment()
    {
        $type = MeteringDevice::where('type_id', $this->payment_type)->pluck('id');
        return $this->hasMany(DeviceRegisterModel::class, 'stead_id', 'stead_id')->whereIn('type_id', $type);
    }

    /**
     * todo зачем это????
     *
     * @param array $attributes
     * @return array
     */
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
//        $history = $this->history;
        $log = Log::addLog($this, $original_model, 'Изменение', $this->stead_id);
        if ($log) {
            $time = date('Y-m-d H:i:s');
//            $history[] = ['date' => $time, 'user_id' => Auth::user()->id, 'data' => $log->value];
            $this->history = '';
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
            $date = date_create_from_format('Y-m-d H:i:s', $item[0]);
            $payment_data = date_format($date, 'Y-m-d H:i:s');
            $payment = new self;
            $payment->price = (float)str_replace(',', '.', $item[1]);
//            $payment->transaction = $item[4];
            $payment->payment_date = $payment_data;
            $payment->discription = '';
            $payment->payment_type = 1;
            $payment->raw_data = $item;
            $payment->user_id = Auth::user()->id;
            $payment->parseType();
            $payment->parseStead();
//            $payment->history = [];

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
     * @deprecated
     * @param BillingBankReestr $reestr
     * @return bool
     */
    public static function createPlaymentOLd(array $item)
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

    /**
     * проверить наличие данного платежа в базе
     *
     * @return bool
     */
    public function checkNoDublicate()
    {
        $find = BillingPayment::query()
            ->where('payment_date', $this->payment_date)
            ->where('price', $this->price)
//            ->where('transaction', $this->transaction)
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
     * todo добавить деталирвки в отдачу
     *
     */
    public function setMeterReading($data)
    {
        $status = true;
        if ($data) {
            foreach ($data as $item) {
                $meter = InstrumentReadings::firstOrNew([
                    'value' => (int)$item['value'],
                    'device_id' =>  $item['device']
                ]);
                $meter->payment_id = $this->id;
                $meter->stead_id = $this->stead_id;
                $meter->value = (int)$item['value'];
                $meter->created_at = $this->payment_date;
                if ($meter->checkForLatestData()) {
                    if (!$meter->logAndSave('Показания из платежки')) {
                        $status = false;
                    }
                } else {
                    $status = false;
                }
            }
//            BillingInvoice::createInvoiceCommunalForPayment($this);
        }
        return $status;
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
    public function parseStead($col = 2)
    {
        $data = $this->raw_data;
        $str = mb_strtolower($data[$col]);
        if ($str) {
            $str = str_replace('-', '/', $str);
            $str = str_replace('-', '/', $str);
            $str = str_replace(',', '/', $str);
            $str = str_replace('участок', '', $str);
            $str = str_replace('№', '', $str);
            $str = str_replace(' ', '', $str);
            $stead = Stead::query()->where('number', 'like', "%{$str}%")->first();
            if (!$stead) {
                $str = str_replace('Л сч 502 10линия', '502', $data['val' . $col]);
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
        } else {
            $error = false;
            $str = mb_strtolower($data[4]);
            $c = explode(';', $str);
            if (count($c) == 4) {
                $stead = Stead::query()->where('number', 'like', "%{$c[2]}%")->first();
                if ($stead) {
                    $this->stead_id = $stead->id;
                }
            }
            if (!$this->stead_id){
                $str = str_replace('№', '', $str);
                $str = str_replace('участки', '!@!', $str);
                $str = str_replace('участка', '!@!', $str);
                $str = str_replace('участок', '!@!', $str);
                $str = str_replace('уч.', '!@!', $str);
                $str = str_replace('уч', '!@!', $str);
                $str = str_replace('лс', '!@!', $str);
                $c = explode('!@!', $str);
                if (count($c) > 1) {
                    $text = trim($c[1]);
                    $text = str_replace(',', ' ', $text);
                    $text = str_replace('.', ' ', $text);
                    $text = str_replace(';', ' ', $text);
                    $n = explode(' ', $text);
                    $stead = Stead::query()->where('number', 'like', "%{$n[0]}%")->first();
                    if ($stead) {
                        $this->stead_id = $stead->id;
                    }
                }
            }
            $this->error = true;
        }
        return $this;
    }


    public function parseType($col = 4)
    {
        $data = $this->raw_data;
        $str = mb_strtolower($data[$col]);
        $typeList = ReceiptType::all();
        $type = false;
        foreach ($typeList as $item) {
            $options = $item->options;
            if (isset($options['tag'])) {
                $tags = $options['tag'];
                foreach ($tags as $tag) {
                    if (stristr($str, $tag) && !$type) {
                        $type = $item->id;
                    }
                }

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
