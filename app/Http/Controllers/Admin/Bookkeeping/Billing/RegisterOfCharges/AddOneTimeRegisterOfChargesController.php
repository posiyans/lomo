<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\RegisterOfCharges;

use App\Http\Resources\Admin\Bookkeeping\AdminInvoiceResource;
use App\Http\Resources\Admin\Bookkeeping\AdminPaymentResource;
use App\Models\Billing\BillingInvoice;
use App\Models\Billing\BillingPayment;
use App\Models\Billing\BillingReestr;
use App\Models\Receipt\InstrumentReadings;
use App\Models\Receipt\MeteringDevice;
use App\Models\Receipt\ReceiptType;
use App\Models\Stead;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;

class AddOneTimeRegisterOfChargesController extends Controller
{


    /**
     * создать разовое начисление
     *
     * @param $title
     * @param $type
     * @param $ratio_a
     * @param $ratio_b
     * @return bool[]|false[]
     */
    public static function addRegister($title, $type, $ratio_a, $ratio_b)
    {
        $reestr = new BillingReestr();
        $reestr->title = $title;
        $reestr->type = 0;
        $options = [];
        if ($ratio_a > 0) {
            $options[] = ['name' => 'Взнос', 'rate' => [
                'ratio_a' => $ratio_a,
                'ratio_b' => 0,
                'description' => $ratio_a . ' руб с сотки'
                ]
            ];
        }
        if ($ratio_b > 0) {
            $options[] = ['name' => 'Взнос', 'rate' => [
                'ratio_a' => 0,
                'ratio_b' => $ratio_b,
                'description' => $ratio_b . ' руб с участка'
                ]
            ];
        }
        $reestr->options = $options;
        DB::beginTransaction();
        if ($reestr->logAndSave('Добавлено разовое начисление')) {
            if (self::setInvoiceForStead($title, $ratio_a, $ratio_b, $type, $reestr->id)) {
                DB::commit();
                return ['status' => true];
            }
        }
        DB::rollBack();
        return ['status' => false];
    }


    public static function setInvoiceForStead($title, $ratio_a, $ratio_b, $type, $reestr_id)
    {
        $steads = Stead::all();
        $status = true;
        foreach ($steads as $stead) {
            $description  = $title. ': ';
            $price = 0;
            $price += $ratio_a * ($stead->size / 100);
            $price += $ratio_b;
            if ($ratio_a > 0 || $ratio_b > 0) {
                if ($ratio_a > 0) {
                   $description .= ($stead->size / 100) .' * '. $ratio_a . ' руб с сотки';
                   $description .= ' = ' . $ratio_a * ($stead->size / 100) . ' руб';
                }
                if ($ratio_b > 0) {
                    $description .= $ratio_b .' руб с участка';
                }
                $description .= ';@';
            }
            $description .= 'Итого: '. $price . ' руб.';
            if (!BillingInvoice::createInvoiceForStead($stead->id, $price, $title, $type, $date = false, $reestr_id, $description)) {
                $status = false;
            }
        }
        return $status;
    }
}
