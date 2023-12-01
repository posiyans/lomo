<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\RegisterOfCharges;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Models\BillingInvoice;
use App\Modules\Billing\Models\BillingReestrModel;
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
        $reestr = BillingReestrModel::find($id);
        $status = true;
        if ($reestr) {
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
