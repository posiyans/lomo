<?php

namespace App\Modules\Billing\Models;

use App\Models\MyModel;
use App\Models\Stead;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


/**
 * App\Modules\Billing\Models\BillingReestrModel
 *
 * @property int $id
 * @property string $title
 * @property int $type
 * @property array $options параметры счета
 * @property int $user_id
 * @property array $history
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Billing\Models\BillingInvoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|BillingReestrModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BillingReestrModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BillingReestrModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BillingReestrModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|BillingReestrModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingReestrModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingReestrModel whereHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingReestrModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingReestrModel whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingReestrModel whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingReestrModel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingReestrModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingReestrModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillingReestrModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BillingReestrModel withoutTrashed()
 * @mixin \Eloquent
 */
class BillingReestrModel extends MyModel
{
    use SoftDeletes;

    protected $casts = [
        'history' => 'array',
        'options' => 'array',
    ];

    public function invoices()
    {
        return $this->hasMany(BillingInvoice::class, 'reestr_id', 'id');
    }

    private $typeList = [
        1 => 'Электроэнергия',
        2 => 'Взносы'
    ];

    /**
     * добавить историю и сохранить
     *
     * @return bool|void
     */
    public function save(array $options = [])
    {
        if (Auth::check()) {
            $this->user_id = Auth::user()->id;
        } else {
            $this->user_id = 0;
        }
//        $history = $this->history;
//        $history[] = [
//            'user_id'=> $this->user_id,
//            'title'=> $this->title,
//            'date'=> time(),
//        ];
        $this->history = [];
        Cache::tags('invoice')->flush();
        return parent::save($options);
    }
    //

    /**
     * создать начисление по взносам
     *
     * @param $user_id
     * @param $title
     * @param $ratio_a
     * @param $ratio_b
     * @return BillingReestrModel|bool
     */
    public static function createСontributions($user_id, $title, $ratio_a, $ratio_b)
    {
        $reestr = new BillingReestrModel();
        $reestr->user_id = $user_id;
        $reestr->title = $title;
        $reestr->ratio_a = $ratio_a;
        $reestr->ratio_b = $ratio_b;
        $reestr->type = 2;
        DB::beginTransaction();
        if ($reestr->save()) {
            $reestr->cretePayment();
            DB::commit();
            return $reestr;
        }
        return false;
    }

    /**
     * обновить начисление по взносам
     *
     * @param $user_id
     * @param $title
     * @param $ratio_a
     * @param $ratio_b
     * @return BillingReestrModel|bool
     */
    public function updateСontributions($user_id, $title, $ratio_a, $ratio_b)
    {
        $this->user_id = $user_id;
        $this->title = $title;
        $this->ratio_a = $ratio_a;
        $this->ratio_b = $ratio_b;
        $this->type = 2;
        DB::beginTransaction();
        if ($this->save()) {
            if ($this->updatePayment()) {
                DB::commit();
                return $this;
            } else {
                DB::rollBack();
            }
        }
        return false;
    }

    /**
     *создать счета для всех участков
     */
    public function cretePayment()
    {
        $steads = Stead::all();
        foreach ($steads as $stead) {
            $price = $this->ratio_a * $stead->size * 0.01 + $this->ratio_b;
            $payment = BillingInvoice::createInvoiceСontributions($stead->id, $this->title, $this->id, $price);
            if (!$payment) {
                DB::rollBack();
            }
        }
    }

    public function updatePayment()
    {
        $status = true;
        foreach ($this->invoices as $invoice) {
            $price = $this->ratio_a * $invoice->stead->size * 0.01 + $this->ratio_b;
            $invoice->price = $price;
            if (!$invoice->save()) {
                $status = false;
                DB::rollBack();
            }
        }
        return $status;
    }
}
