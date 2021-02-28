<?php

namespace App\Models;

use App\Models\Gardening;
use App\Models\Stead;

use App\Models\Receipt\ReceiptType;


use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;


class QrCodeModel
{
    //
    public $out;
    public $QRcode;

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


    public function fillDetailsUserInStead($stead)
    {
        if (is_int($stead)){
            $stead = Stead::findOrFail($stead);
        }
        $fio = isset($stead->descriptions['fio']) ? $stead->descriptions['fio'] : '';
        $str = strpos($fio, "19");
        if ($str) {
            $fio = substr($fio, 0, $str);
        }
        $ar = explode(' ', $fio);
        if ($ar[0] != ''){
            $this->out .= '|LastName=' . $ar[0];
        }
        if (count($ar) > 1) {
            $this->out .= '|FirstName=' .  $ar[1];
        }
        if (count($ar) > 2) {
            $this->out .= '|MiddleName=' . $ar[2];
        }
    }

    /**
     * установить ФИО плательщика
     *
     * @param $fio
     */
    public function fillFioUser($fio)
    {
        $args = explode(' ', $fio);
        $field = ['|LastName=', '|FirstName=', '|MiddleName='];
        for ($i=0; $i < 3; $i++) {
            if (key_exists($i, $args)){
                $this->out .= $field[$i] . $args[$i];
            }
        }
        if (count($args)> 3){
            unset($args[0], $args[1],$args[2]);
            $this->out .= ' '.implode(' ', $args);
        }
      }

    /**
     * установить назначение платежа
     *
     * @param $description
     */
    public function setDescription($description){
        $this->out .= '|Purpose=' . mb_substr($description, 0, 115);
    }

    /**
     * установить номер участка
     *
     * @param $number
     */
    public function setSteadNumber($number){
        $this->out .= '|PersAcc=' . $number;
    }

    /**
     * установить сумму платежа в копейках
     *
     * @param int $cash
     */
    public function setCash(int $cash){
        if ($cash > 0) {
            $this->out .= '|Sum=' . $cash;
        }
    }

    public function fillDetailsDevice($ReceiptType, $steadModel)
    {
        if (is_int($ReceiptType)){
           $ReceiptType = ReceiptType::findOrFail((int)$ReceiptType);
        }
        if (is_int($steadModel)){
            $steadModel = Stead::findOrFail((int)$steadModel);
        }
        $cash = 0;
        $description = '';
        foreach ($ReceiptType->MeteringDevice as $MeteringDevice) {
            $cash += $MeteringDevice->getTicket($steadModel->id);
            $description .= $MeteringDevice->name . ' ' . $MeteringDevice->cash_description . ' ';
        }
//        полное назначение не проходит в банк клиенте
//        $this->out .= '|Purpose=' . $ReceiptType->name . ' ' . $discription;
        $this->out .= '|Purpose=' . $ReceiptType->name . ' участок ' . $steadModel->number;
        $this->out .= '|PersAcc=' . $steadModel->number;
        if ($cash > 0) {
            $this->out .= '|Sum=' . $cash*100;
        }
    }

    public function render()
    {
        $this->QRcode = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($this->out)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->build();
//            ->logoPath(__DIR__.'/assets/symfony.png')
//            ->labelText('This is the label')
//            ->labelFont(new NotoSans(20))
//            ->labelAlignment(new LabelAlignmentCenter())

    }

    public function getPng(){
        $this->render();
        return $this->QRcode->getString();
    }

    public function getFile($name){
        $this->render();
        return $this->QRcode->saveToFile($name);

    }

}
