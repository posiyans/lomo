<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing;

use App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\BalanceList\GetListController;
use App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\Repository\BalanceForSteadRepository;
use App\Http\Resources\Admin\Bookkeeping\AdminBalansSteadResource;
use App\Http\Resources\Admin\Bookkeeping\AdminInvoiceResource;
use App\Http\Resources\Admin\Bookkeeping\AdminPaymentResource;
use App\Models\Billing\BillingInvoice;
use App\Models\Billing\BillingPayment;
use App\Models\Billing\BillingReestr;
use App\Models\Receipt\ReceiptType;
use App\Models\Stead;
use App\Models\Laratrust\Permission;
use App\Models\Laratrust\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class BalanceController extends Controller
{


    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    /**
     * список участков  балансом
     *
     * $request->category  с долгами, или без
     * $request->payment когда был последний платеж
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $data = GetListController::getData($request);
        $total = count($data);
        $offset = ($request->page - 1) * $request->limit;
        $steads = $this->paginate($data, $request->limit, $request->page);
        return ['status' => true, 'total' => $total, 'offset' => $offset, 'data' => AdminBalansSteadResource::collection($steads)];

    }

    public function info(Request $request)
    {
        $stead_id = $request->get('stead_id', false);
        $type = $request->get('type', false);
        $receipt_type = $request->get('receiptType', false);
        $stead = Stead::find($stead_id);
        if (!$stead) {
            throw new \Exception('Участок не найден');
        }
        $items = (new BalanceForSteadRepository())->getForStead($stead_id, $type, $receipt_type);
        $rezult['total'] = count($items);
        $rezult['data'] = array_values($this->paginate($items, $request->limit, $request->page));
        $rezult['status'] = true;
        return $rezult;
    }


    public function allBalance(Request $request)
    {
       $stead = Stead::find($request->id);
        $types = ReceiptType::all();
        $result = ['balans_all' => round($stead->getBalans(), 2)];
        $temp_balans = [];
        foreach ($types as $type) {
            $temp_balans[] = ['name' => $type->name, 'price' => round($stead->getBalans($type->id), 2)];
        }
        $result['balans'] = $temp_balans;
       return ['status' => true, 'data' => $result];
    }
}
