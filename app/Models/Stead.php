<?php

namespace App\Models;

use App\Models\Owner\OwnerUserSteadModel;
use App\Modules\Billing\Models\BillingInvoice;
use App\Modules\Billing\Models\BillingPaymentModel;
use App\Modules\Receipt\Models\DeviceRegisterModel;
use App\Modules\Receipt\Models\InstrumentReadingModel;
use App\Modules\Receipt\Models\ReceiptTypeModels;
use App\Modules\Stead\Models\SteadModel;
use Illuminate\Support\Facades\Cache;


/**
 * Модель участков
 * @deprecated
 * @property int $id
 * @property int $gardening_id
 * @property string $number
 * @property int|null $user_id
 * @property string|null $surname
 * @property string|null $name
 * @property string|null $patronymic
 * @property string|null $size
 * @property string|null $email
 * @property string|null $history
 * @property array|null $descriptions
 * @property array|null $options опции для участка
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BillingInvoice> $Invoices
 * @property-read int|null $invoices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OwnerUserSteadModel> $Owners
 * @property-read int|null $owners_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Billing\Models\BillingPaymentModel> $Payment
 * @property-read int|null $payment_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \App\Models\Gardening|null $gardient
 * @property-read \Illuminate\Database\Eloquent\Collection<int, InstrumentReadingModel> $indications
 * @property-read int|null $indications_count
 * @property-read BillingPaymentModel|null $lastPayment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|Stead newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stead newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stead query()
 * @method static \Illuminate\Database\Eloquent\Builder|Stead whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stead whereDescriptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stead whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stead whereGardeningId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stead whereHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stead whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stead whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stead whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stead whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stead wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stead whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stead whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stead whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stead whereUserId($value)
 * @mixin \Eloquent
 */
class Stead extends SteadModel
{
    protected $fillable = ['number', 'descriptions'];
    protected $casts = [
        'descriptions' => 'array',
        'options' => 'array',
    ];
    //

    public $userFullName = '';


    /**
     * получить все платежи по участку
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Owners()
    {
        return $this->hasMany(OwnerUserSteadModel::class, 'stead_id', 'id');
    }
//    /**
//     * отношение с пользователем
//     * @return \Illuminate\Database\Eloquent\Relations\HasOne
//     */
//    public function user()
//    {
//        return $this->hasOne(User::class, 'id', 'user_id');
//    }
//
//    public function userFullName()
//    {
//        if ($this->user){
//            $this->userFullName = $this->user->fullName();
//            return $this->userFullName;
//        }
//        return '';
//    }

    /**
     * получить показания приборов по участку
     * todo вопрос зачем все показания
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function indications()
    {
        return $this->hasMany(InstrumentReadingModel::class, 'steads_id', 'id');
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

    /**
     * Сохрание данных об участке
     * todo передалать создать сущьность владелец
     * @param $request
     * @deprecated
     */
    public function saveData($request)
    {
        $this->surname = isset($request->surname) ? $request->surname : $this->surname;
        $this->name = isset($request->name) ? $request->name : $this->name;
        $this->patronymic = isset($request->patronymic) ? $request->patronymic : $this->patronymic;
        $this->size = isset($request->size) ? $request->size : $this->size;
        $this->email = isset($request->email) ? $request->email : $this->email;
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
    public static function addNote($id, $note)
    {
        $stead = Stead::find($id);
        if ($stead) {
            $descriptions = $stead->descriptions;
            if (isset($descriptions['note'])) {
                $descriptions['note'] .= "\n" . $note;
            } else {
                $descriptions['note'] = $note;
            }
            $stead->descriptions = $descriptions;
            if ($stead->logAndSave('Изменение в описании', $stead->id)) {
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
        $this->number = $model['number'];
        $this->size = $model['size'];
        $this->descriptions = $model['descriptions'];
        $this->options = $model['options'];
        if ($this->logAndSave('Изменение данных об участке', $this->id)) {
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
        return $this->hasMany(BillingPaymentModel::class, 'stead_id', 'id');
    }

    /**
     * получить платежи определеннного типа
     *
     * @param $type
     * @return mixed
     */
    public function PaymentForReceipt($type)
    {
        return BillingPaymentModel::where('stead_id', $this->id)->where('type', $type)->get();
    }

    /**
     * получить последний платеж по участку
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastPayment()
    {
        return $this->hasOne(BillingPaymentModel::class, 'stead_id', 'id')->orderBy('payment_date', 'DESC');
    }


//    public function PaymentContributions()
//    {
//        return $this->hasMany(BillingPaymentModel::class, 'stead_id', 'id')->where('type', 2);
//    }
//
//    public function PaymentCommunal()
//    {
//        return $this->hasMany(BillingPaymentModel::class, 'stead_id', 'id')->where('type', 1);
//    }


    /**
     * получить баланс участка
     *
     * @param false $type по всем платежам или кокретному типу
     * @return int
     */
    public function getBalans($type = false)
    {
        $keyName = 'balans_stead-' . $this->id . '_type-' . $type;
        return Cache::tags(['balans', 'invoice', 'payment'])->remember($keyName, 6000, function () use ($type) {
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
        });
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
        if ($device->logAndSave('Добавлен прибор', $this->id)) {
            return $device;
        }
        return false;
    }


    /**
     * получить массив приброров опрделенного типа по участку
     *
     * @param \App\Modules\Receipt\Models\ReceiptTypeModels $receipt_type тип взносов
     * @return mixed
     */
    public function getMeteringDevice(ReceiptTypeModels $receipt_type)
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
