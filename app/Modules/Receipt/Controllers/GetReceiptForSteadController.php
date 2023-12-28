<?php

namespace App\Modules\Receipt\Controllers;

use App\Http\Controllers\Admin\Report\PrimaryPdfController;
use App\Http\Controllers\Controller;
use App\Modules\Gardening\Repositories\GardeningRepository;
use App\Modules\Rate\Repositories\RateGroupRepository;
use App\Modules\Receipt\Validators\GetReceiptForSteadValidator;
use App\Modules\Stead\Repositories\SteadRepository;

/**
 * todo позжк переделать под все типы тирифов
 *
 * Получить квитанцию на участок
 */
class GetReceiptForSteadController extends Controller
{


    public function __invoke(GetReceiptForSteadValidator $request)
    {
        $stead = (new SteadRepository())->byId($request->stead_id);
//        return $request->validated();
        $rate_group = (new RateGroupRepository())->byId($request->rate_group_id);
        // todo for $rate_group->depends == 1
        $purpose = '';
        $description = $rate_group->name . '@';
        $sum = 0;
        if ($rate_group->depends == 1) {
            foreach ($rate_group->rateType as $rate_type) {
                $rate = $rate_type->currentRate;
                $s = round(($stead->size / 100) * $rate->ratio_a + $rate->ratio_b, 2);
                $purpose .= $rate_type->name . '=' . $s . ', ';
                $description .= $rate_type->name . ' ' . $stead->size . ' м.кв * ' . $rate->description . '=' . $s . ' руб.@';
                $sum += $s;
            }
            $purpose = mb_substr($purpose, 0, -2);
            $description .= 'Итого = ' . $sum . ' руб.';
        }
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
        $options = [
            'stead_num' => $stead->number,
            'purpose' => $rate_group->name . ' ' . $purpose,
            'description' => $description,
            'sum' => $sum
        ];
        $pdf->addPage($options);
        $pdf->render();
        $tmpfname = tempnam(sys_get_temp_dir(), 'ticket_stead_' . $stead->id);
        $pdf->Output($tmpfname);
        return response()->download($tmpfname, 'Квитанция_участок_' . $stead->number . '.pdf');
    }

}
