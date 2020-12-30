<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing;

use App\Http\Resources\Admin\Bookkeeping\AdminBalansSteadResource;
use App\Models\Billing\BillingBankReestr;
use App\Models\Billing\BillingInvoice;
use App\Models\Billing\BillingPayment;
use App\Models\Billing\BillingReestr;
use App\Models\Stead;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

;

class BankReestrController extends Controller
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
    public function list(Request $request)
    {
//        $query = BillingInvoice::query();
//        $article = $query->orderBy('created_at', 'desc')->paginate($request->limit);
//        return ['status'=>true, 'data'=>$article, 'total'=>$article->total()];
        $query = BillingBankReestr::query();
        $query->where('file_publish', false);
        $reestrs = $query->paginate($request->limit);
//        $rezult = [];
//        foreach ($steads as $stead) {
//            $rezult[] = $stead->balans = $stead->getBalans();
//        }
//        $reestrs['status'] = true;
//        return $reestrs;
        return ['status'=>true, 'data'=>$reestrs, 'total'=>$reestrs->total()];
    }
//
    public function info(Request $request)
    {
        $rezult = [];
        $reestr = BillingBankReestr::find($request->id);
//        $rezult['stead_info'] = new AdminBalansSteadResource($stead);
//        $rezult['invoices'] = BillingInvoice::getInvocesForStead($stead->id);
//        $reestr->data['legend'] = [0,1,5,6,7,8];
        $data = $reestr->data;
        $data['legend'] = [0,1,5,6,7,8];
        $reestr->data = $data;
//        $reestr->parseType();
//        $reestr->parseStead();
        return ['status'=>true, 'data'=>$reestr];
//        $query = BillingReestr::query();
//        $article = $query->orderBy('created_at', 'desc')->paginate($request->limit);
//        return ['status'=>true, 'data'=>$article, 'total'=>$article->total()];
    }


    public function uploadReestr(Request $request)
    {
        if ($request->data && is_array($request->data)) {
                $user = Auth::user();
                $data = [];
            foreach ($request->data as $item) {
                $data[] = BillingPayment::createPlaymen($item);
                }

                $file = new BillingBankReestr();
                $file->file_hash = '-';
//                $file->file_name = $inputFile->getClientOriginalName();
//                $file->file_size = $inputFile->getSize();
                $file->user_id = $user->id;
                $file->parseData($request->data);
                $file->parseType();
                $file->parseStead();
                $file->findDublicate();
                if ($file->save()) {
                    return json_encode(['status' => true, 'data' => $file]);
                }
                return json_encode(['status' => false, 'data' => 'Ошибка при сохранении файла']);
//            }
//            return json_encode(['status' => false, 'data'=>'Фаил имеет неверный формат']);
        }
        return json_encode(['status' => false, 'data'=>'Фаил не загружен']);
    }



    public function uploadReestrOld(Request $request)
    {
        if (Input::hasFile('file')) {
            $inputFile = Input::file('file');
            if (BillingBankReestr::checkFile($inputFile)) {
                $user = Auth::user();
                $md5 = $this->md5_file($inputFile);
                $inputFile->move($md5['folder'], $md5['md5']);
                $file = new BillingBankReestr();
                $file->file_hash = $md5['md5'];
                $file->file_name = $inputFile->getClientOriginalName();
                $file->file_size = $inputFile->getSize();
                $file->user_id = $user->id;
                $file->parseData();
                $file->parseType();
                $file->parseStead();
                $file->findDublicate();
                if ($file->save()) {
                    return json_encode(['status' => true, 'data' => $file]);
                }
                return json_encode(['status' => false, 'data' => 'Ошибка при сохранении файла']);
            }
            return json_encode(['status' => false, 'data'=>'Фаил имеет неверный формат']);
        }
        return json_encode(['status' => false, 'data'=>'Фаил не загружен']);
    }

    public function parseReestr(Request $request)
    {
        if ($request->reestr_id){
            $reestr = BillingBankReestr::find($request->reestr_id);
            if($reestr) {
//                $reestr->parseData();
                $reestr->parseType();
                $reestr->parseStead();
                $reestr->findDublicate();
                if ($request->action && $request->action == 'save'){
                  $reestr->save();
                }
                return $reestr;
            }
        }
    }


    public function update(Request $request)
    {
        if ($request->reestr && $request->reestr['data']){
            $data=$request->reestr['data'];
            $id = $request->reestr['id'];
            $reestr = BillingBankReestr::find($id);
            if ($request && $reestr->created_at == $request->reestr['created_at']) {
                $reestr->data = $data;
                if ($reestr->save()) {
                    return json_encode(['status'=>true, 'data'=>$reestr]);
                }
            }
            return json_encode(['status'=>false]);
        }
    }

    public function publish(Request $request)
    {
        if ($request->reestr && $request->reestr['data']){
            $data=$request->reestr['data'];
            $id = $request->reestr['id'];
            $reestr = BillingBankReestr::find($id);
            if ($reestr->created_at && $reestr->created_at == $request->reestr['created_at']) {
                $reestr->data = $data;
                if ($reestr->save()) {
                    //todo пока некуда разносить
                    DB::beginTransaction();
                    $rez = BillingPayment::smashTheReestr($reestr);
                    if ($rez){
                        $reestr->file_publish = true;
                        if ($reestr->save()) {
                            DB::commit();
                            return json_encode(['status'=>true, 'data'=>$reestr]);
                        }
                    }
                    DB::rollBack();
                }
            }
            return json_encode(['status'=>false]);
        }
    }
}
