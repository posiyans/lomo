<?php

namespace App\Modules\Receipt\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Gardening\Repositories\GardeningRepository;
use App\Modules\QrCode\Actions\CreateQrCodeAction;

class GetQrCodeForGardeningController extends Controller
{

    public function __invoke()
    {
        $gardient = (new GardeningRepository())->all();
        $code = new CreateQrCodeAction();
        $out = 'ST00012';
        $out .= isset($gardient['name']) ? '|Name=' . $gardient['name'] : '';
        $out .= isset($gardient['PersonalAcc']) ? '|PersonalAcc=' . $gardient['PersonalAcc'] : '';
        $out .= isset($gardient['BankName']) ? '|BankName=' . $gardient['BankName'] : '';
        $out .= isset($gardient['BIC']) ? '|BIC=' . $gardient['BIC'] : '';
        $out .= isset($gardient['CorrespAcc']) ? '|CorrespAcc=' . $gardient['CorrespAcc'] : '';
        $out .= isset($gardient['PayeeINN']) ? '|PayeeINN=' . $gardient['PayeeINN'] : '';
        $code->setText($out);
        return $code->getPng();
    }

}
