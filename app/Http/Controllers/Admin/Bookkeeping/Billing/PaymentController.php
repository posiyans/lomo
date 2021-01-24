<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing;

use App\Http\Resources\Admin\Bookkeeping\AdminPaymentResource;
use App\Models\Billing\BillingPayment;
use App\Models\Billing\BillingReestr;
use App\Models\Stead;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class PaymentController extends Controller
{


    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = BillingPayment::query();
        if ($request->find) {
            $query->where('price', 'like', "%$request->find%");
            $query->orWhere('transaction', 'like', "%$request->find%");
            $steads = Stead::query()->where('number', 'like', "%$request->find%")->get(['id']);
            $query->orWhereIn('stead_id', $steads);
        }
        if ($request->date && is_array($request->date)) {
            $query->whereBetween('payment_date', [$request->date[0],  $request->date[1].' 23:59:59']);
        }
        if ($request->status && $request->status == 1) {
            $query->where('error', 1);
        }
        if ($request->status && $request->status == 2) {
            $query->where('error', '!=', 1);
        }


//        if ($request->type) {
//            $query->where('type', $request->type);
//        }
        $items = $query->orderBy('payment_date', 'desc')->paginate($request->limit);
        return AdminPaymentResource::collection($items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return 'store';
//        if (isset($request->title) && !empty($request->title)) {
//            $ratio_a = (isset($request->ratio_a) && !empty($request->ratio_a)) ? $request->ratio_a : false;
//            $ratio_b = (isset($request->ratio_b) && !empty($request->ratio_b)) ? $request->ratio_b : false;
//            if ($ratio_a || $ratio_b) {
//                $user_id = Auth::user()->id;
//                $reestr = BillingReestr::createСontributions($user_id, $request->title, $ratio_a,  $ratio_b);
//                if ($reestr) {
//                    return ['status'=>true, 'data'=>$reestr];
//                }
//                //todo нужно поставить симафор!!!!
//                return ['status'=>false, 'data'=>'Ошибка при сохранении'];
//            }
//        }
//        return ['status'=>false, 'data'=>'Нет данных'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data= BillingPayment::find($id);
        return ['status' => true, 'data' => new AdminPaymentResource($data)];
//        return json_encode(['status'=>true, 'data'=>$data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $payment = BillingPayment::find($id);
        $payment->stead_id = $request->has('stead_id') ? $request->stead_id :  $payment->stead_id;
        $payment->type = $request->type ? $request->type : $payment->type;
        $payment->discription = $request->has('discription') ? $request->discription : $payment->discription;
        $payment->error = $request->has('error') ? $request->error : $payment->error;

//        $payment->raw_data = $request->raw_data ? $request->raw_data : $payment->raw_data;
        if ($payment->save()) {
            $device = true;
            if ($request->type_depends) {
                $device = $payment->setMeterReading($request->instr_read);
            }
            return json_encode(['status'=>true, 'data'=>new AdminPaymentResource($payment), 'device' => $device]);
        }
        return json_encode(['status'=>false]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
