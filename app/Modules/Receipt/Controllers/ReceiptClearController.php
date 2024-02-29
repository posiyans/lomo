<?php

namespace App\Modules\Receipt\Controllers;

use App\Http\Controllers\Admin\Report\PrimaryPdfController;
use App\Http\Controllers\Controller;
use App\Modules\Gardening\Repositories\GardeningRepository;


class ReceiptClearController extends Controller
{


    public function __invoke()
    {
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
        $options = [];
        $pdf->addPage($options);
        $pdf->render();
        $tmpfname = tempnam(sys_get_temp_dir(), 'ticket_stead_clear');
        $pdf->Output($tmpfname);
        return response()->download($tmpfname, 'Ticket_CHT.pdf');
    }

}
