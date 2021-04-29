<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\BalanceForStead;


use App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\Repository\BalanceForSteadRepository;
use App\Http\Controllers\Admin\AbstractAdminController;
use App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\BalanceForStead\Classes\XlsxFileResource;

use App\Models\Receipt\ReceiptType;
use App\Models\Stead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BalanceForSteadXlsxFileController extends AbstractAdminController
{

     /**
     * Добавить счета  группк показаний
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $stead_id = $request->get('stead_id', false);
            $type = $request->get('type', false);
            $receipt_type = $request->get('receiptType', false);
            $stead = Stead::find($stead_id);
            if (!$stead) {
                throw new \Exception('Участок не найден');
            }
            $items = (new BalanceForSteadRepository())->getForStead($stead_id, $type, $receipt_type);
            (new XlsxFileResource())->render($items, $stead->number);
        } catch (\Exception $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
//        $this->generateXLSX($data);

//        (new XlsxFileResource())->render($data);
    }


}
