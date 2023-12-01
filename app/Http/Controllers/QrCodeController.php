<?php

namespace App\Http\Controllers;

use App\Models\Gardening;
use App\Models\QrCodeModel;
use App\Models\Stead;
use App\Modules\Receipt\Models\ReceiptTypeModels;

class QrCodeController extends Controller
{
    //

    public function getImage($id, $name)
    {
        $steadModel = Stead::find(session('stead_id'));
        $gardient = $steadModel->gardient;
        $ReceiptType = ReceiptTypeModels::findOrFail((int)$id);
        $code = new QrCodeModel;
        $code->fillDetailsGardient($gardient);
        $code->fillDetailsUser($steadModel);
        $code->fillDetailsDevice($ReceiptType, $steadModel);
        return $code->getPng();
    }

    public function qrCodeClear()
    {
        $gardient = Gardening::find(1);
        $code = new QrCodeModel;
        $code->fillDetailsGardient($gardient);
        return $code->getPng();
    }


}
