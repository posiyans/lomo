<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing;

use App\Http\Resources\Admin\Bookkeeping\AdminPaymentResource;
use App\Models\Billing\BillingPayment;
use App\Models\Billing\BillingReestr;
use App\Models\Stead;
use App\Models\Laratrust\Permission;
use App\Models\Laratrust\Role;
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
        $this->middleware('ability:superAdmin,reestr-edit');
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
        if ($request->price && is_numeric($request->price) && $request->stead['id']) {
            $payment = new BillingPayment();
            $payment->fill($request->all());
            $raw = [
                date('y-m-d H:i:s'),
                $request->price,
                $request->stead['number'],
                'Платеж внесен в кассу',
                $request->title,
            ];
            $payment->raw_data = $raw;
            $payment->price = $request->price;
            $payment->stead_id  = $request->stead['id'];
            $payment->user_id = Auth::user()->id;
            $payment->payment_type = 2;
            $payment->payment_date = date('y-m-d H:i:s');
            if ($payment->logAndSave('Добавлен счет')) {
                return ['status' => true, 'data' => $payment];
            }
        }
        return ['status' => false];
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
        $payment->description = $request->has('description') ? $request->description : $payment->description;
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
