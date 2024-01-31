<?php

namespace App\Modules\Billing\Models;

use App\Models\MyModel;
use App\Modules\MeteringDevice\Models\InstrumentReadingModel;
use App\Modules\Rate\Models\RateGroupModel;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Modules\Billing\Models\BillingPaymentModel
 *
 * @property int $id
 * @property int|null $stead_id id участка
 * @property string|null $description
 * @property int|null $type тип платежа
 * @property float $price
 * @property string|null $transaction
 * @property string $payment_date
 * @property int|null $reestr_id
 * @property int $payment_type
 * @property array $raw_data
 * @property int|null $invoice_id
 * @property int|null $user_id
 * @property bool $error есть ли неточности в строке
 * @property array $history
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\MeteringDevice\Models\InstrumentReadingModel> $instrumentReadings
 * @property-read int|null $instrument_readings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @property-read Stead|null $stead
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel whereHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel whereRawData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel whereReestrId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel whereSteadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel whereTransaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingPaymentModel whereUserId($value)
 * @mixin \Eloquent
 */
class BillingPaymentModel extends MyModel
{
    use SoftDeletes;

    //
    protected $casts = [
        'raw_data' => 'array',
        'price' => 'decimal:2',
        'error' => 'boolean'
    ];

    protected $fillable = ['type', 'description'];

    /**
     * участок который платил
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stead()
    {
        return $this->hasOne(SteadModel::class, 'id', 'stead_id');
    }

    /**
     * группа тарифов к которой относится платеж
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rate_group()
    {
        return $this->hasOne(RateGroupModel::class, 'id', 'rate_group_id');
    }

    /**
     * участок который платил
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function invoice()
    {
        return $this->hasOne(BillingInvoiceModel::class, 'id', 'invoice_id');
    }

    /**
     * показания приборов за который заплатили
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instrument_readings()
    {
        return $this->hasMany(InstrumentReadingModel::class, 'payment_id', 'id');
    }


////    public function getType()
////    {
////        return $this->hasOne(ReceiptTypeModels::class, 'id', 'type');
////    }
////
////    public function getDeviceReestrForPayment()
////    {
////        $type = MeteringDevice::where('type_id', $this->payment_type)->pluck('id');
////        return $this->hasMany(DeviceRegisterModel::class, 'stead_id', 'stead_id')->whereIn('type_id', $type);
////    }
//
////    /**
////     * todo зачем это????
////     *
////     * @param array $attributes
////     * @return array
////     */
////    public function steadObject(array $attributes = [])
////    {
////        $data = [];
////        if ($this->stead) {
////            if (count($attributes) == 0) {
////                $attributes = $this->stead->getAttributes();
////            }
////            foreach ($attributes as $item) {
////                if(isset($this->stead[$item])) {
////                  $data[$item] = $this->stead[$item];
////                }
////            }
////        }
////        return $data;
////    }
//
//    /**
//     * разнести платежку по счетам садоводов
//     *
//     * @param BillingBankReestr $reestr
//     * @return bool
//     */
//    public static function createPlayment(array $item)
//    {
//        try {
//            $date = date_create_from_format('Y-m-d H:i:s', $item[0]);
//            $payment_data = date_format($date, 'Y-m-d H:i:s');
//            $payment = new self;
//            $payment->price = (float)str_replace(',', '.', $item[1]);
//            $payment->payment_date = $payment_data;
//            $payment->description = '';
//            $payment->payment_type = 1;
//            $payment->raw_data = $item;
//            $payment->user_id = Auth::user()->id;
//            $payment->parseType();
//            $payment->parseStead();
//            if ($payment->checkNoDublicate()) {
//                if ($payment->save()) {
//                    return $payment;
//                }
//            } else {
//                $payment->dubl = true;
//                return $payment;
//            }
//        } catch (\Exception $e) {
//            return false;
//        }
//        return false;
//    }
//
//
//    /**
//     * разнести платежку по счетам садоводов
//     *
//     * @param BillingBankReestr $reestr
//     * @return bool
//     * @deprecated
//     */
//    public static function createPlaymentOLd(array $item)
//    {
//        try {
//            $date = date_create_from_format('d-m-Y H-i-s', $item[0] . ' ' . $item[1]);
//            $payment_data = date_format($date, 'Y-m-d H:i:s');
//            $payment = new self;
//            $payment->price = (float)str_replace(',', '.', $item[8]);
//            $payment->transaction = $item[4];
//            $payment->payment_date = $payment_data;
//            $payment->description = '';
//            $payment->payment_type = 1;
//            $payment->raw_data = $item;
//            $payment->user_id = Auth::user()->id;
//            $payment->parseType();
//            $payment->parseStead();
//            if ($payment->checkNoDublicate()) {
//                if ($payment->save()) {
//                    return $payment;
//                }
//            } else {
//                $payment->dubl = true;
//                return $payment;
//            }
//        } catch (\Exception $e) {
//            return false;
//        }
//        return false;
//    }
//
//
//    /**
//     * разнести платежку по счетам садоводов
//     *
//     * @param BillingBankReestr $reestr
//     * @return bool
//     */
//    public static function smashTheReestr(BillingBankReestr $reestr)
//    {
//        try {
//            $data = $reestr->data;
//            $status = true;
//            foreach ($data['data'] as $item) {
//                $temp_date = explode('-', $item['val0']);
//                $temp_time = explode('-', $item['val1']);
//                $payment_data = $temp_date[2] . '-' . $temp_date[1] . '-' . $temp_date[0] . ' ' . $temp_time[0] . ':' . $temp_time[1] . ':' . $temp_time[2];
//                $payment = new self;
//                $payment->price = (float)str_replace(',', '.', $item['val8']);
//                $payment->transaction = $item['val4'];
//                $payment->payment_date = $payment_data;
//                if ($payment->checkNoDublicate()) {
//                    $payment->stead_id = $item['stead']['id'];
//                    $payment->description = $item['val7'];
//                    $payment->type = $item['type'];
//                    $payment->reestr_id = $reestr->id;
//                    $payment->payment_type = 1;
//                    $payment->raw_data = $item;
//                    $payment->user_id = Auth::user()->id;
////                dump($payment);
////                dump($payment->checkNoDublicate());
////                if ($payment->checkNoDublicate()) {
//                    if (!$payment->save()) {
//                        $status = false;
//                    }
//                }
//            }
//            return $status;
//        } catch (\Exception $e) {
//            echo $e;
//            return false;
//        }
//        return true;
//    }
//
//    /**
//     * проверить наличие данного платежа в базе
//     *
//     * @return bool
//     */
//    public function checkNoDublicate()
//    {
//        $find = BillingPaymentModel::query()
//            ->where('payment_date', $this->payment_date)
//            ->where('price', $this->price)
////            ->where('transaction', $this->transaction)
//            ->first();
//        if ($find) {
//            return false;
//        } else {
//            return true;
//        }
//    }
//
//    /**
//     * получить счета для участка
//     *
//     * @param $stead_id
//     * @param false $type
//     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
//     */
//    public static function getPaymentForStead($stead_id, $type = false)
//    {
//        $query = BillingPaymentModel::query()->where('stead_id', $stead_id);
//        if ($type) {
//            $query->where('type', $type);
//        }
//        $invoices = $query->orderBy('created_at')->get();
//        return $invoices;
//    }
//
//
//    /**
//     * установить показания счетчиков по этому платежу
//     * todo добавить деталирвки в отдачу
//     *
//     */
//    public function setMeterReading($data)
//    {
//        $status = true;
//        if ($data) {
//            foreach ($data as $item) {
//                $meter = InstrumentReadingModel::firstOrNew([
//                    'value' => (int)$item['value'],
//                    'device_id' => $item['device']
//                ]);
//                $meter->payment_id = $this->id;
//                $meter->stead_id = $this->stead_id;
//                $meter->value = (int)$item['value'];
//                $meter->created_at = $this->payment_date;
//                if ($meter->checkForLatestData()) {
//                    if (!$meter->logAndSave('Показания из платежки')) {
//                        $status = false;
//                    }
//                } else {
//                    $status = false;
//                }
//            }
////            BillingInvoice::createInvoiceCommunalForPayment($this);
//        }
//        return $status;
//    }
//
//    /**
//     *удалить показания счетчиков по этому платежу
//     */
//    public function deleteMeterReading()
//    {
//        $items = InstrumentReadingModel::query()->where(['payment_id' => $this->id])->get();
//        if ($items) {
//            foreach ($items as $item) {
//                $item->delete();
//            }
//        }
//    }
//
//
//    /**попробывать получить номер участка
//     *
//     *
//     * @param int $col
//     * @return $this
//     */
//    public function parseStead($col = 2)
//    {
//        try {
//            $data = $this->raw_data;
//            $str = mb_strtolower($data[$col]);
//            if ($str) {
//                $str = str_replace('-', '/', $str);
//                $str = str_replace('-', '/', $str);
//                $str = str_replace(',', '/', $str);
//                $str = str_replace('участок', '', $str);
//                $str = str_replace('№', '', $str);
//                $str = str_replace(' ', '', $str);
//                $str = preg_replace('/[^0-9\/]/', '', $str);
//                $stead = Stead::query()->where('number', 'like', "%{$str}%")->first();
//                if (!$stead) {
//                    $str = str_replace('Л сч 502 10линия', '502', $data[$col]);
//                    $str = str_replace('Л сч502 10линия', '502', $str);
//                    $str = str_replace('288, 289', '288', $str);
//                    $str = str_replace('526/525', '525/526', $str);
//                    $stead = Stead::query()->where('number', 'like', "%{$str}%")->first();
//                }
//                if ($stead) {
//                    $this->stead_id = $stead->id;
//                }
//            } else {
//                $str = mb_strtolower($data[4]);
//                $c = explode(';', $str);
//                if (count($c) == 4) {
//                    $stead = Stead::query()->where('number', 'like', "%{$c[2]}%")->first();
//                    if ($stead) {
//                        $this->stead_id = $stead->id;
//                    }
//                }
//                if (!$this->stead_id) {
//                    $str = str_replace('№', '', $str);
//                    $str = str_replace('участки', '!@!', $str);
//                    $str = str_replace('участка', '!@!', $str);
//                    $str = str_replace('участок', '!@!', $str);
//                    $str = str_replace('уч.', '!@!', $str);
//                    $str = str_replace('уч', '!@!', $str);
//                    $str = str_replace('лс', '!@!', $str);
//                    $c = explode('!@!', $str);
//                    if (count($c) > 1) {
//                        $text = trim($c[1]);
//                        $text = str_replace(',', ' ', $text);
//                        $text = str_replace('.', ' ', $text);
//                        $text = str_replace(';', ' ', $text);
//                        $n = explode(' ', $text);
//                        $stead = Stead::query()->where('number', 'like', "%{$n[0]}%")->first();
//                        if ($stead) {
//                            $this->stead_id = $stead->id;
//                        }
//                    }
//                }
//            }
//            if (!$this->stead_id || $stead->number != mb_strtolower($data[$col])) {
//                $this->error = true;
//            }
//            return $this;
//        } catch (\Exception $e) {
//            $this->error = true;
//            return $this;
//        }
//    }
//
//
//    public function parseType($col = 4)
//    {
//        $data = $this->raw_data;
//        $str = mb_strtolower($data[$col]);
//        $typeList = ReceiptTypeModels::all();
//        $type = false;
//        foreach ($typeList as $item) {
//            $options = $item->options;
//            if (isset($options['tag'])) {
//                $tags = $options['tag'];
//                foreach ($tags as $tag) {
//                    if (stristr($str, $tag) && !$type) {
//                        $type = $item->id;
//                    }
//                }
//            }
//        }
//        if ($type) {
//            $this->type = $type;
//        } else {
//            $this->error = true;
//        }
//        return $this;
//    }

}
