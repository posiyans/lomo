<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\InstrumentReadings;

use App\Http\Resources\Admin\Bookkeeping\AdminInvoiceResource;
use App\Http\Resources\Admin\Bookkeeping\AdminPaymentResource;
use App\Models\Billing\BillingInvoice;
use App\Models\Billing\BillingPayment;
use App\Models\Billing\BillingReestr;
use App\Models\Receipt\InstrumentReadings;
use App\Models\Laratrust\Permission;
use App\Models\Laratrust\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AddInvoiceController extends Controller
{




    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    /**
     * Добавить счет для показания
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id , Request $request)
    {
        $item = InstrumentReadings::find($id);
        if ($item) {
            $price = $item->getPrice();
            $old = $item->getPreviousReadings();
            $stead_id = $item->deviceRegister->stead_id;
            $receipt_type = $item->deviceRegister->meteringDevice->type_id;
            $delta = $item->value - $old;
            if ($price > 0 && $delta > 0) {
               $summa = $delta *  $price;
               $title = $item->deviceTypeName();
               $title = $title[1]. ' ' . $delta .'('.$item->value.')';
               $invoice = BillingInvoice::createInvoiceForStead($stead_id, $summa, $title,  $receipt_type, $item->created_at);
               if ($invoice) {
                   $item->invoice_id = $invoice->id;
                   $item->save();
                   return ['status' => true, 'data' => $invoice];
               }
            }
        }
        return ['status' => false];
    }



}
