<?php

namespace App\Http\Controllers\Admin\Receipt;

use App\Http\Controllers\Admin\Report\PdfController;
use App\Http\Controllers\Admin\Report\PrimaryPdfController;
use App\Http\Controllers\Controller;
use App\Models\Billing\BillingInvoice;
use App\Models\Receipt\ReceiptType;
use Illuminate\Http\Request;
use App\Models\Stead;
use App\Models\Receipt\InstrumentReadings;
use App\Models\Receipt\MeteringDevice;
use App\Models\Gardening;


class GetReceiptForInvoicesController extends Controller
{
    protected $query;

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
        $this->query = BillingInvoice::query();
    }


    /**
     * получить квитанции в pdf на оплату счетов
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $invoices_ids = $request->get('invoices', false);
        if ($invoices_ids && is_array($invoices_ids)) {
            $invoices = BillingInvoice::whereIn('id', $invoices_ids)->get();
            $pdf = new PrimaryPdfController();
            $gardieng = Gardening::find(1);
            $options = [
                'name' => $gardieng->name,
                'inn' => $gardieng->PayeeINN,
                'personal_acc' => $gardieng->PersonalAcc,
                'bank_name' => $gardieng->BankName,
                'bic' => $gardieng->BIC,
                'cor_acc' => $gardieng->CorrespAcc,
            ];
            $pdf->setGlobalVar($options);
            foreach ($invoices as $invoice) {
                $options = [
                    'stead_num' => $invoice->steadNumber(),
//                    'last_name' => 'Иванов',
//                    'first_name' => 'Петр',
//                    'middle_name' => 'Иванович',
                    'purpose' => $invoice->title,
                    'description' => $invoice->description,
                    'sum' => $invoice->price
                ];
                $pdf->addPage($options);
            }
            $pdf->render();
            return $pdf->Output('tickets.pdf');
        }
    }

}
