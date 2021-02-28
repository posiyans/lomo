<?php
namespace App\Models;

use App\Models\Billing\BillingInvoice;
use App\Models\Billing\BillingPayment;
use App\Models\Receipt\DeviceRegisterModel;
use App\Models\Receipt\MeteringDevice;
use App\Models\Receipt\ReceiptType;
use App\Models\MyModel;
use App\Models\Receipt\InstrumentReadings;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;


/**
 * Модель участков
 */
class Stead extends MyModel
{
    protected $fillable = ['number', 'descriptions'];
    protected $casts = [
        'descriptions' => 'array',
        'options' => 'array',
    ];
    //

    public $userFullName = '';
    /**
     * отношение с пользователем
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function userFullName()
    {
        if ($this->user){
            $this->userFullName = $this->user->fullName();
            return $this->userFullName;
        }
        return '';
    }

    /**
     * получить показания приборов по участку
     * todo вопрос зачем все показания
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function indications()
    {
        return $this->hasMany(InstrumentReadings::class, 'steads_id', 'id');
    }

    /**
     * отношение с садоводством
     * todo вырезать так как садоводство всего одно
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function gardient()
    {
        return $this->hasOne(Gardening::class, 'id', 'gardening_id');
    }

//    public function MeteringDevice()
//    {
//        return $this->hasMany(MeteringDevice::class, 'steads_id', 'id');
//    }

//    public function getIndication($n=false) {
//        $devices = MeteringDevice::where('enable', 1)->get();
//        $ind= [];
//        foreach ($devices as $device){
//            $indications = InstrumentReadings::where('type_id', $device->id)
//                ->where('stead_id', $this->id)
//                ->orderBy('created_at', 'desc')
//                ->limit(2)
//                ->get();
//            $device->val_new = isset($indications[0]->value) ? $indications[0]->value : 0;
//            $device->val_old = isset($indications[1]->value) ? $indications[1]->value : 0;
//            $device->value = $device->val_new - $device->val_old;
//            $device->rateNow();
//            $device->cash = $device->value * $device->rate->ratio_a  + $device->rate->ratio_b;
//            $ind[$device->id] = $device;
//        }
//        $this->Indications = $ind;
//    }


    /**
     * Сохрание данных об участке
     * todo передалать создать сущьность владелец
     *
     * @param $request
     */
    public function saveData($request){
        $this->surname  = isset($request->surname)  ? $request->surname : $this->surname ;
        $this->name = isset($request->name) ? $request->name: $this->name;
        $this->patronymic = isset($request->patronymic) ? $request->patronymic: $this->patronymic;
        $this->size = isset($request->size) ? $request->size: $this->size;
        $this->email = isset($request->email) ? $request->email: $this->email;
        $this->save();
    }


//    public function setSession($request){
//
//        session(['stead_id' => $this->id]);
//        isset($request->surname) ? (['surname' =>  $request->surname]):'';
//        isset($request->name) ? session(['name' => $request->name]):'';
//        isset($request->patronymic) ? session(['patronymic' => $request->patronymic]):'';
//        isset($request->size) ? session(['stead_size' => $request->size]):'';
//    }


    /**
     * добавить примечание к участку
     *
     * @param $id -- id участка
     * @param $note -- примечание
     * @return bool
     */
    public static function addNote($id, $note){
        $stead = Stead::find($id);
        if ($stead){
            $descriptions = $stead->descriptions;
            if (isset($descriptions['note'])){
                $descriptions['note'] .= "\n".$note;
            } else {
                $descriptions['note'] = $note;
            }
            $stead->descriptions = $descriptions;
            if ($stead->logAndSave('Изменение в описании', $stead->id)){
                return $stead;
            }
        }
        return false;
    }

    /**
     * обновить данные участка
     * @param $id
     * @param $model
     * @return bool
     */
    public function updateStead($model)
    {
        $this->number =  $model['number'];
        $this->size =  $model['size'];
        $this->descriptions =  $model['descriptions'];
        $this->options =  $model['options'];
        if ($this->logAndSave('Изменение данных об участке', $this->id)){
            return $this;
        }
        return false;
    }


    /**
     * получить все счета по участку
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Invoices()
    {
        return $this->hasMany(BillingInvoice::class, 'stead_id', 'id');
    }

    /**
     * получить счета кокретного типа по участку
     *
     * @param $type
     * @return mixed
     */
    public function InvoicesForReceipt($type)
    {
        return BillingInvoice::where('stead_id', $this->id)->where('type', $type)->get();
    }

    /**
     * получить все платежи по участку
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Payment()
    {
        return $this->hasMany(BillingPayment::class, 'stead_id', 'id');
    }

    /**
     * получить платежи определеннного типа
     *
     * @param $type
     * @return mixed
     */
    public function PaymentForReceipt($type)
    {
        return BillingPayment::where('stead_id', $this->id)->where('type', $type)->get();
    }

    /**
     * получить последний платеж по участку
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastPayment()
    {
        return $this->hasOne(BillingPayment::class, 'stead_id', 'id')->orderBy('payment_date', 'DESC');
    }


//    public function PaymentContributions()
//    {
//        return $this->hasMany(BillingPayment::class, 'stead_id', 'id')->where('type', 2);
//    }
//
//    public function PaymentCommunal()
//    {
//        return $this->hasMany(BillingPayment::class, 'stead_id', 'id')->where('type', 1);
//    }


    /**
     * получить баланс участка
     *
     * @param false $type по всем платежам или кокретному типу
     * @return int
     */
    public function getBalans($type = false)
    {
        $balans = 0;
        if (!$type) {
            foreach ($this->invoices as $invoice) {
                $balans -= $invoice->price;
            }
            foreach ($this->payment as $invoice) {
                $balans += $invoice->price;
            }
        } else {
            foreach ($this->InvoicesForReceipt($type) as $invoice) {
                $balans -= $invoice->price;
            }
            foreach ($this->PaymentForReceipt($type) as $invoice) {
                $balans += $invoice->price;
            }
        }
        return $balans;
    }


    /**
     * добавить прибор учета для участка
     * todo переснести отсюда!!!
     *
     * @param $options
     * @return DeviceRegisterModel|false
     */
    public function addMeteringDevice($options)
    {
        $device = new DeviceRegisterModel();
        $device->fill($options);
        if ($device->logAndSave('Добавлен прибор', $this->id))
        {
            return $device;
        }
        return false;
    }


    /**
     * получить массив приброров опрделенного типа по участку
     *
     * @param ReceiptType $receipt_type тип взносов
     * @return mixed
     */
    public function getMeteringDevice(ReceiptType $receipt_type)
    {
        if ($receipt_type->depends == 2) {
            $metering_device = $receipt_type->MeteringDevice;
            $ar = $metering_device->map(function ($i) {
                return $i->id;
            });
            return DeviceRegisterModel::whereIn('type_id', $ar)->where('stead_id', $this->id)->where('active', 1)->get();
        }
        return false;
    }



//    public function lastPayment()
//    {
//        return $this->hasMany(BillingInvoice::class, 'stead_id', 'id');
//    }

}
