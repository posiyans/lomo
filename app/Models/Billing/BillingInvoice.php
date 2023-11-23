<?php

namespace App\Models\Billing;

use App\Models\Receipt\InstrumentReadings;
use App\Models\Receipt\ReceiptType;
use App\Models\Stead;
use App\Models\MyModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Billing\BillingInvoice
 *
 * @property int $id
 * @property string $title
 * @property int $stead_id
 * @property int $type
 * @property int|null $reestr_id
 * @property float $price
 * @property int|null $payment_id
 * @property bool $paid оплачен счет?
 * @property int|null $user_id
 * @property string|null $description подробное описание
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read ReceiptType|null $ReceiptType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, InstrumentReadings> $metersData
 * @property-read int|null $meters_data_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Billing\BillingPayment> $payments
 * @property-read int|null $payments_count
 * @property-read Stead|null $stead
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice whereReestrId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice whereSteadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BillingInvoice withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, InstrumentReadings> $metersData
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Billing\BillingPayment> $payments
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, InstrumentReadings> $metersData
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Billing\BillingPayment> $payments
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, InstrumentReadings> $metersData
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Billing\BillingPayment> $payments
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, InstrumentReadings> $metersData
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Billing\BillingPayment> $payments
 * @mixin \Eloquent
 */
class BillingInvoice extends MyModel
{

    use SoftDeletes;

    protected $fillable = ['title', 'type', 'description'];

    protected $casts = [
        'paid' => 'boolean',
        'price' => 'float',
    ];



    /**
     * сохранить и очисть зависящие кеши
     *
     * @return bool|void
     */
    public function save(array $options = [])
    {
        Cache::tags('invoice')->flush();
        return parent::save($options);
    }


    public function payments()
    {
        return $this->hasMany(BillingPayment::class, 'invoice_id', 'id');
    }



    /**
     * получить участок счета
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function metersData()
    {
        return $this->hasMany(InstrumentReadings::class, 'invoice_id', 'id');
    }

    public function ReceiptType()
    {
        return $this->hasOne(ReceiptType::class, 'id', 'type');
    }

    /**
     * получить участок счета
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
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

//    /**
//     *создать счет на электроэнергию на основании показаний из платежки
//     */
//    public static function createInvoiceCommunalForPayment()
//    {
//
//    }

    /**
     * создать счет на взносы для участка
     *
     * @param $stead_id
     * @param $title
     * @param $reestr_id
     * @param $price
     * @return BillingInvoice|false
     */
    public static function createInvoiceСontributions($stead_id, $title, $reestr_id, $price)
    {
        $invoce = new BillingInvoice();
        $invoce->stead_id = $stead_id;
        $invoce->title = $title;
        $invoce->reestr_id = $reestr_id;
        $invoce->price = $price;
        $invoce->type = 2;
        if ($invoce->save()){
            return $invoce;
        }
        return false;
    }

    /**
     * добавить счет
     *
     * @param $stead_id участок
     * @param $price сумма
     * @param $title счет на что
     * @param $receipt_type тип счета
     * @return BillingInvoice|false
     */
    public static function createInvoiceForStead($stead_id, $price, $title, $receipt_type, $date = false, $reestr = null, $description = '')
    {
        $invoice = new self();
        $invoice->title = $title;
        $invoice->stead_id = $stead_id;
        $invoice->type = $receipt_type;
        $invoice->price = (float)$price;
        $invoice->reestr_id = $reestr;
        $invoice->description = $description;
        if ($date) {
            $invoice->created_at = $date;
        }
        if ($invoice->logAndSave('Добавлен счет', $stead_id)) {
            return $invoice;
        }
        return false;
    }


    /**
     * получить счета для участка
     *
     * @param $stead_id
     * @param false $type
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getInvocesForStead($stead_id, $type = false)
    {
        $query = BillingInvoice::query()->where('stead_id', $stead_id);
        if ($type) {
            $query->where('type', $type);
        }
        $invoices  = $query->orderBy('created_at')->get();
        return $invoices;
    }


    /**
     * установить что платеж от этого счета
     *
     * @param BillingPayment $payment
     * @return bool
     */
    public function match(BillingPayment $payment)
    {
        $payment->invoice_id = $this->id;
        if ($payment->logAndSave('Сопоставили платеж')) {
            return true;
        }
        return false;
    }
}
