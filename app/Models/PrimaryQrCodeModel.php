<?php

namespace App\Models;


use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class PrimaryQrCodeModel
{
    //
    public $out;

    public function __construct()
    {
        $this->out = 'ST00012';
    }

    public function setDetailsGardient($data)
    {
        $this->out .= isset($data['name']) ? '|Name=' . $data['name']: '';
        $this->out .= isset($data['personal_acc']) ? '|PersonalAcc=' . $data['personal_acc'] : '';
        $this->out .= isset($data['bank_name']) ? '|BankName=' . $data['bank_name'] : '';
        $this->out .= isset($data['bic']) ? '|BIC=' . $data['bic'] : '';
        $this->out .= isset($data['cor_acc']) ? '|CorrespAcc=' . $data['cor_acc'] : '';
        $this->out .= isset($data['inn']) ? '|PayeeINN=' . $data['inn'] : '';
    }

    public function setDetailsUser($data)
    {
        $this->out .= isset($data['last_name'])  ? '|LastName=' . $data['last_name'] : '';
        $this->out .= isset($data['first_name']) ? '|FirstName=' . $data['first_name'] : '';
        $this->out .= isset($data['middle_name']) ? '|MiddleName=' . $data['middle_name']: '';
    }

    /**
     * установить назначение платежа
     *
     * @param $description
     */
    public function setDescription($description){
        if ($description) {
            $this->out .= '|Purpose=' . $description;
        }
    }

    /**
     * установить номер участка
     *
     * @param $number
     */
    public function setSteadNumber($number){
        if ($number) {
            $this->out .= '|PersAcc=' . $number;
        }
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
    public function render()
    {
        $this->QRcode = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($this->out)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(350)
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

    public function getFile($name = false){
        $this->render();
        if ($name) {
            return $this->QRcode->saveToFile($name);
        }
        return $this->QRcode->getString();
    }


}
