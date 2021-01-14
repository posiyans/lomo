<?php

namespace App\Models\Billing;

use App\Models\Receipt\InstrumentReadings;
use App\Models\Stead;
use Illuminate\Database\Eloquent\Model;

class BillingInvoice extends Model
{


    /**
     * получить участок счета
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function metersData()
    {
        return $this->hasMany(InstrumentReadings::class, 'invoice_id', 'id');
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

}
