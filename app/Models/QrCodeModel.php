<?php

namespace App\Models;

use App\Models\Gardening;
use App\Models\Stead;

use App\Models\ReceiptType;

class QrCodeModel
{
    //
    public $out;

    public function __construct()
    {
        $this->out = 'ST00012';
    }

    public function fillDetailsGardient($gardient)
    {
        if (is_int($gardient)){
            $gardient = Gardening::findOrFail($gardient);
        }
        if ($gardient instanceof Gardening) {
            $this->out .= isset($gardient->name) ? '|Name=' . $gardient->name : '';
            $this->out .= isset($gardient->PersonalAcc) ? '|PersonalAcc=' . $gardient->PersonalAcc : '';
            $this->out .= isset($gardient->BankName) ? '|BankName=' . $gardient->BankName : '';
            $this->out .= isset($gardient->BIC) ? '|BIC=' . $gardient->BIC : '';
            $this->out .= isset($gardient->CorrespAcc) ? '|CorrespAcc=' . $gardient->CorrespAcc : '';
            $this->out .= isset($gardient->PayeeINN) ? '|PayeeINN=' . $gardient->PayeeINN : '';
        }
    }

    public function fillDetailsUser($stead)
    {
        if (is_int($stead)){
            $stead = Stead::findOrFail($stead);
        }
        $this->out .= session('surname') ? '|LastName=' . session('surname') : '|LastName=' . $stead->surname;
        $this->out .= session('name') ? '|FirstName=' . session('name') : '|FirstName=' . $stead->name;
        $this->out .= session('patronymic') ? '|MiddleName=' . session('patronymic') : '|MiddleName=' . $stead->patronymic;
    }

    public function fillDetailsDevice($ReceiptType, $steadModel)
    {
        //$ReceiptType = ReceiptType::findOrFail((int)$id);
        $cash = 0;
        $discription = '';
        foreach ($ReceiptType->MeteringDevice as $MeteringDevice) {
            $cash += $MeteringDevice->getTicket($steadModel->id);
            $discription .= $MeteringDevice->name . ' ' . $MeteringDevice->cash_description . ' ';
        }
        $this->out .= '|Purpose=' . $ReceiptType->name . ' ' . $discription;
        $this->out .= '|PersAcc=' . $steadModel->number;
        if ($cash > 0) {
            $this->out .= '|Sum=' . $cash*100;
        }
    }

    public function getPng(){
        return \PHPQRCode\QRcode::png($this->out,
            $outfile = false,
            $level = 2,
            $size = 3,
            $margin = 4);
    }

    public function getFile($name){
        return \PHPQRCode\QRcode::png($this->out,
            $outfile = $name,
            $level = 2,
            $size = 3,
            $margin = 4);
    }

}
