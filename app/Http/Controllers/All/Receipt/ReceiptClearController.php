<?php

namespace App\Http\Controllers\All\Receipt;

use App\Http\Controllers\Admin\Report\PdfController;
use App\Http\Controllers\Admin\Report\PrimaryPdfController;
use App\Http\Controllers\Controller;
use App\Models\Receipt\ReceiptType;
use Illuminate\Http\Request;
use App\Models\Stead;
use App\Models\Receipt\InstrumentReadings;
use App\Models\Receipt\MeteringDevice;
use App\Models\Gardening;


class ReceiptClearController extends Controller
{



    public function index()
    {
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
        $options = [];
        $pdf->addPage($options);
        $pdf->render();
        return $pdf->Output('Ticket_CHT.pdf');
    }

}
