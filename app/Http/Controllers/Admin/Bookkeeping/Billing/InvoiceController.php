<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing;

use App\Http\Resources\Admin\Bookkeeping\AdminInvoiceResource;
use App\Http\Resources\Admin\Bookkeeping\AdminPaymentResource;
use App\Models\Billing\BillingInvoice;
use App\Models\Billing\BillingPayment;
use App\Models\Billing\BillingReestr;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class InvoiceController extends Controller
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
     * Добавить счет
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->price && is_numeric($request->price) && $request->stead['id']) {
            $invoice = new BillingInvoice();
            $invoice->fill($request->all());
            $invoice->price = $request->price;
            $invoice->stead_id  = $request->stead['id'];
            $invoice->user_id = Auth::user()->id;
            if ($invoice->logAndSave('Добавлен счет')) {
                return ['status' => true, 'data' => $invoice];
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
        $data= BillingInvoice::find($id);
        $rez =  new AdminInvoiceResource($data);
        return ['data' => $rez, 'status' => true];
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
        $invoice = BillingInvoice::find($id);
        if ($invoice->id == $request->id) {
            $invoice->description = $request->has('description') ? $request->description : $invoice->description;
        }
        if ($invoice->save()) {
            return json_encode(['status'=>true, 'data'=> new AdminInvoiceResource($invoice)]);
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
