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

class  DeleteRegisterOfChargesController extends Controller
{


    /**
     * Удалить начисление и все его счита
     *
     */
    public static function deleteRegister($id)
    {
        $reestr = BillingReestr::find($id);
        $status = true;
        if ($reestr){
            DB::beginTransaction();
             if ($reestr->delete()) {
                 $invoices = BillingInvoice::where('reestr_id', $id)->get();
                 foreach ($invoices as $item) {
                     if (!$item->delete()) {
                         $status = false;
                     }
                 }
             } else {
                 $status = false;
             }
             if ($status) {
                 DB::commit();
                 return ['status' => true];
             }
            DB::rollBack();
        }
        return ['status' => false];
    }



}
