<?php

namespace App\Models\Billing;

use App\Models\Stead;
use Illuminate\Database\Eloquent\Model;
use DB;

class BillingReestr extends Model
{
    protected $casts = [
        'history' => 'array',
        'ratio_a'=> 'float',
        'ratio_b'=> 'float',
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
        $history = $this->history;
        $history[] = [
            'user_id'=> $this->user_id,
            'title'=> $this->title,
            'ratio_a'=> $this->ratio_a,
            'ratio_b'=> $this->ratio_b,
            'date'=> time(),
        ];
        $this->history = $history;
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
     * @return BillingReestr|bool
     */
    public static function createСontributions($user_id, $title, $ratio_a, $ratio_b)
    {

        $reestr = new BillingReestr();
        $reestr->user_id  = $user_id;
        $reestr->title  = $title;
        $reestr->ratio_a  = $ratio_a;
        $reestr->ratio_b  = $ratio_b;
        $reestr->type  = 2;
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
     * @return BillingReestr|bool
     */
    public function updateСontributions($user_id, $title, $ratio_a, $ratio_b)
    {

        $this->user_id  = $user_id;
        $this->title  = $title;
        $this->ratio_a  = $ratio_a;
        $this->ratio_b  = $ratio_b;
        $this->type  = 2;
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
            $payment = BillingInvoice::createInviceСontributions($stead->id, $this->title, $this->id, $price);
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
            if (!$invoice->save()){
                $status = false;
                DB::rollBack();
            }
        }
        return $status;
    }
}
