<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing;

use App\Http\Resources\Admin\Bookkeeping\AdminPaymentResource;
use App\Models\Billing\BillingPayment;
use App\Models\Billing\BillingReestr;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;;

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
        return 'index';
//        $query = BillingReestr::query();
//        if ($request->find) {
//            $query->where('title', 'like', "%$request->find%");
//        }
//        if ($request->type) {
//            $query->where('type', $request->type);
//        }
//        $article = $query->orderBy('created_at', 'desc')->paginate($request->limit);
//        return ['status'=>true, 'data'=>$article, 'total'=>$article->total()];
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
        return new AdminPaymentResource($data);
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
        $payment->stead_id = $request->stead_id;
        $payment->payment_type = $request->payment_type;
        $payment->raw_data = $request->raw_data;
        if ($payment->save()) {
            $payment->setMeterReading();
            return json_encode(['status'=>true, 'data'=>$payment]);
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
