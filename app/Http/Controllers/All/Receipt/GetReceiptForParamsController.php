<?php

namespace App\Http\Controllers\All\Receipt;

use App\Http\Controllers\Admin\Report\PrimaryPdfController;
use App\Http\Controllers\Controller;
use App\Models\Gardening;
use Illuminate\Http\Request;


class GetReceiptForParamsController extends Controller
{


    public function index(Request $request)
    {
        sleep(5);
        $stead_num = $request->post('stead_num', false);
        $purpose = $request->post('purpose', false);
        $sum = $request->post('sum', false);
        $description = $request->post('description', false);
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
        $options = compact('stead_num', 'purpose', 'sum', 'description');
        $pdf->addPage($options);
        $pdf->render();
        return $pdf->Output('Ticket_CHT.pdf');
    }

}
