<?php

namespace App\Modules\Receipt\Controllers;

use App\Http\Controllers\Admin\Report\PrimaryPdfController;
use App\Http\Controllers\Controller;
use App\Modules\Billing\Repositories\InvoiceRepository;
use App\Modules\Gardening\Repositories\GardeningRepository;
use App\Modules\Receipt\Validators\GetReceiptForInvoiceValidator;

/**
 *
 * Получить квитанцию на счет
 */
class GetReceiptForInvoiceController extends Controller
{


    public function __invoke(GetReceiptForInvoiceValidator $request)
    {
        $invoice = (new InvoiceRepository())->byId($request->invoice_id);
        $stead = $invoice->stead;
        $pdf = new PrimaryPdfController();
        $gardieng = (new GardeningRepository())->all();
        $options = [
            'name' => $gardieng['name'],
            'inn' => $gardieng['PayeeINN'],
            'personal_acc' => $gardieng['PersonalAcc'],
            'bank_name' => $gardieng['BankName'],
            'bic' => $gardieng['BIC'],
            'cor_acc' => $gardieng['CorrespAcc'],
        ];
        $pdf->setGlobalVar($options);
        //todo что делать с несколькими собственниками
        $options = [
            'stead_num' => $stead->number,
            'purpose' => $invoice->title,
            'description' => $invoice->description['description'] ?? '',
            'sum' => $invoice->price,
            'last_name' => $stead->owners[0]->getValue('surname', '') ?? '',
            'first_name' => $stead->owners[0]->getValue('name', '') ?? '',
            'middle_name' => $stead->owners[0]->getValue('middle_name', '') ?? '',
        ];
        $pdf->addPage($options);
        $pdf->render();
        $tmpfname = tempnam(sys_get_temp_dir(), 'ticket_stead_' . $stead->id);
        $pdf->Output($tmpfname);
        $stead_number = str_replace('/', '-', $stead->number);
        return response()->download($tmpfname, 'Счет_' . $invoice->id . '_уч_' . $stead_number . '.pdf');
    }

}
