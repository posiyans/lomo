<?php

namespace App\Modules\QrCode\Actions;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;


class CreateQrCodeAction
{
    //
    private $out;
    private $QRcode;


    public function setText($text)
    {
        $this->out = $text;
        return $this;
    }

    public function render()
    {
        $this->QRcode = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($this->out)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(600)
            ->margin(40)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->build();
//            ->logoPath(__DIR__.'/assets/symfony.png')
//            ->labelText('This is the label')
//            ->labelFont(new NotoSans(20))
//            ->labelAlignment(new LabelAlignmentCenter())

    }

    public function getPng()
    {
        $this->render();
        return $this->QRcode->getString();
    }

    public function getFile($name)
    {
        $this->render();
        return $this->QRcode->saveToFile($name);
    }

}
