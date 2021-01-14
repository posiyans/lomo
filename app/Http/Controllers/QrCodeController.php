<?php

namespace App\Http\Controllers;

use App\Models\Gardening;
use Illuminate\Http\Request;
use App\Models\QrCodeModel;
use App\Models\Stead;
use App\Models\Receipt\ReceiptType;

class QrCodeController extends Controller
{
    //

    public function getImage($id, $name)
    {
        $steadModel = Stead::find(session('stead_id'));
        $gardient = $steadModel->gardient;
        $ReceiptType = ReceiptType::findOrFail((int)$id);
        $code = new QrCodeModel;
        $code->fillDetailsGardient($gardient);
        $code->fillDetailsUser($steadModel);
        $code->fillDetailsDevice($ReceiptType, $steadModel);
        return $code->getPng();
    }

    public function qrCodeClear(){
        $gardient = Gardening::find(1);
        $code = new QrCodeModel;
        $code->fillDetailsGardient($gardient);
        return $code->getPng();
    }




}
