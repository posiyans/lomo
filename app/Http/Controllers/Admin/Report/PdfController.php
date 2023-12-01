<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Gardening;
use App\Models\QrCodeModel;
use App\Models\Stead;
use App\Modules\Receipt\Models\ReceiptTypeModels;

class PdfController extends Controller
{
    //


    public static function getReceipFoStead($stead_id, $ReceiptType, $fio = false)
    {
        $steads = Stead::findOrFail($stead_id);
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        self::createClearReceipt($pdf);
        self::createQRcode($pdf, $stead_id, $fio);
        self::fillGadeningData($pdf);
        self::fillUserData($pdf, $stead_id, $fio);
        self::fillAmountData($pdf, $ReceiptType, $stead_id);
        return $pdf->Output('ticket_' . $steads->number . '.pdf', 'S');
    }

    public static function getReceipFoSteadsList($steads, $ReceiptType, $reestr = false, $fio = false)
    {
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        if ($reestr) {
            self::createRegistryPage($pdf, 2, $steads, $fio);
        }
        foreach ($steads as $stead) {
            $pdf->AddPage();
            self::createClearReceipt($pdf);
            self::createQRcode($pdf, $stead->id, $fio);
            self::fillGadeningData($pdf);
            self::fillUserData($pdf, $stead->id, $fio);
            self::fillAmountData($pdf, $ReceiptType, $stead->id);
        }
        $pdf->Output('tickets.pdf', 'I');
    }

    public function report()
    {
        $ReceiptType = 2;
        set_time_limit(700);
        $steads = Stead::where('id', '<', 5)->get();
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $this->createRegistryPage($pdf, 2, $steads);
        foreach ($steads as $steadModel) {
            $pdf->AddPage();
            $this->createClearReceipt($pdf);
            $this->createQRcode($pdf, $steadModel->id);
            $this->fillGadeningData($pdf);
            //$this->fillUserData($pdf, $steadModel);
            $this->fillAmountData($pdf, $ReceiptType, $steadModel->id);
        }
        $pdf->Output('rr.pdf', 'I');
    }

    public static function fillAmountData($pdf, $ReceiptType, $steadModel)
    {
        if (is_int($ReceiptType) || is_string($ReceiptType)) {
            $ReceiptType = ReceiptTypeModels::find($ReceiptType);
        }
        if (is_int($steadModel) || is_string($steadModel)) {
            $steadModel = Stead::find($steadModel);
        }
        $description = '';
        $cash = 0;
        mb_internal_encoding('UTF-8');
        foreach ($ReceiptType->MeteringDevice as $MeteringDevice) {
            $cash += $MeteringDevice->getTicket($steadModel->id);
            $description .= $MeteringDevice->name . ' ' . $MeteringDevice->cash_description . ' ';
        }
        $text = $ReceiptType->name . ' ' . $description;
        $s_arr = [-0.5, 70];
        $pdf->SetFont('freesans', '', 9);
        foreach ($s_arr as $s) {
            $pdf->Text(88, $s + 63, mb_substr($text, 0, 60));
            $pdf->Text(55, $s + 68, mb_substr($text, 60));
            $pdf->Text(82, $s + 74, floor($cash));
            $pdf->Text(103.5, $s + 74, round(fmod($cash, 1) * 100));
        }
    }

    public static function fillUserData($pdf, $stead_id, $fio_print = false)
    {
        $steadModel = Stead::find($stead_id);
        if ($steadModel) {
            $fio = isset($steadModel->descriptions['fio']) ? $steadModel->descriptions['fio'] : '';
            $fio = str_replace('8', ' ', $fio);
            $fio = str_replace('(', ' ', $fio);
            $fio = str_replace('?', ' ', $fio);
            $fio = str_replace('1', ' ', $fio);
            $str = strpos($fio, "19");
            if ($str) {
                $fio = substr($fio, 0, $str);
            }
            $ar = explode(' ', $fio);
            $fio = $ar[0];
            if (count($ar) > 1) {
                $fio .= ' ' . $ar[1];
            }
            if (count($ar) > 2) {
                $fio .= ' ' . $ar[2];
            }
            $s_arr = [-0.5, 70];
            foreach ($s_arr as $s) {
                $pdf->SetFont('freesans', '', 10);
                if ($fio_print) {
                    $pdf->Text(88, $s + 58, $fio);
                }
                $pdf->SetFont('freesans', 'B', 10);
                $pdf->Text(75, $s + 51, $steadModel->number);
            }
        }
    }

    public static function fillGadeningData($pdf)
    {
        $gardient = Gardening::find(1);
        $s_arr = [-0.5, 70];
        foreach ($s_arr as $s) {
            $pdf->SetFont('freesans', 'B', 10);
            $skip = 120 - strlen($gardient->name) * 0.5;
            $pdf->Text($skip, $s + 27.5, $gardient->name);
            $pdf->SetFont('freesans', '', 7);
            $pdf->Text(55, $s + 42.5, 'в');
            $pdf->Text(59, $s + 42.5, $gardient->BankName);
            $pdf->SetFont('freesans', 'B', 10);
            //ИНН получателя платежа (12-значный)
            $a = 99;
            $arr = str_split($gardient->PayeeINN);
            $len = count($arr);
            for ($i = 1; $i <= $len; $i++) {
                $pdf->Text($a, $s + 34.8, $arr[$len - $i]);
                $a = $a - 4;
            }

//номер счета получателя платежа (20-значный)

            $a = 112;
            $arr = str_split($gardient->PersonalAcc);
            for ($i = 0; $i < 20; $i++) {
                $pdf->Text($a, $s + 34.8, $arr[$i]);
                $a = $a + 4;
            }

//БИК (9-значный)

            $a = 156;
            $arr = str_split($gardient->BIC);
            for ($i = 0; $i < 9; $i++) {
                $pdf->Text($a, $s + 41.8, $arr[$i]);
                $a = $a + 4;
            }

//Номер кор./сч.банка получателя платежа (20-значный)

            $a = 112;
            $arr = str_split($gardient->CorrespAcc);
            for ($i = 0; $i < 20; $i++) {
                $pdf->Text($a, $s + 46.7, $arr[$i]);
                $a = $a + 4;
            }
        }
    }


    public static function createRegistryPage($pdf, $ReceiptType, $steads, $fio_print = false)
    {
        if (is_int($ReceiptType)) {
            $ReceiptType = ReceiptTypeModels::findOrFail($ReceiptType);
        }
        $pdf->AddPage();
        $pdf->setFontStretching(105);
        $pdf->SetFont('freesans', '', 9);
        $pdf->SetFillColor(206, 239, 236);
        $pdf->Cell(20, 6, 'Номер', 1, 0, 'C', 1);
        $pdf->Cell(25, 6, 'Размер, м.кв', 1, 0, 'C', 1);
        if ($fio_print) {
            $pdf->Cell(60, 6, 'ФИО', 1, 0, 'C', 1);
            $l = 60;
        } else {
            $pdf->SetFont('freesans', '', 6);
            $l = 0;
            foreach ($ReceiptType->MeteringDevice as $MeteringDevice) {
                $pdf->Cell(18, 6, $MeteringDevice->name, 1, 0, 'C', 1);
                $l += 18;
            }
            $pdf->SetFont('freesans', '', 9);
        }
        $pdf->Cell(30, 6, 'Сумма', 1, 0, 'C', 1);
        $pdf->Cell(50, 6, 'Примечание', 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetFillColor(224, 235, 255);
        $fill = 0;
        foreach ($steads as $key => $value) {
//            $pdf->Cell(10, 6, $key, 'LR', 0, 'C', $fill);
            $pdf->Cell(20, 6, $value->number, 'LR', 0, 'C', $fill);
            $pdf->Cell(25, 6, $value->size, 'LR', 0, 'C', $fill);
            $fio = isset($value->descriptions['fio']) ? $value->descriptions['fio'] : '';
            $fio = str_replace('8', ' ', $fio);
            $fio = str_replace('(', ' ', $fio);
            $fio = str_replace('?', ' ', $fio);
            $fio = str_replace('1', ' ', $fio);
//            $str = strpos($fio, "19");
//            if ($str) {
//                $fio = substr($fio, 0, $str);
//            }
            $ar = explode(' ', $fio);
            $fio = $ar[0];
            if (count($ar) > 1) {
                $fio .= ' ' . $ar[1];
            }
            if (count($ar) > 2) {
                $fio .= ' ' . $ar[2];
            }
            if ($fio_print) {
                $pdf->Cell(60, 6, $fio, 'LR', 0, 'C', $fill);
            }
            $cash = 0;
            foreach ($ReceiptType->MeteringDevice as $MeteringDevice) {
                if (!$fio_print) {
                    $pdf->Cell(18, 6, $MeteringDevice->getTicket($value->id), 'LR', 0, 'C', $fill);
                }
                $cash += $MeteringDevice->getTicket($value->id);
            }
            $pdf->Cell(30, 6, $cash . ' руб.', 'LR', 0, 'C', $fill);
            $pdf->Cell(50, 6, '', 1, 0, 'C', $fill);
            $pdf->Ln();
            $fill = !$fill;
        }
        $pdf->Cell(90 + $l, 0, '', 'T');
    }

    public static function createQRcode($pdf, $stead_id, $fio = false)
    {
        $pdf->SetFont('freesans', '', 6);
        $pdf->Text(15, 30, 'Код для оплаты в терминалах,');
        $pdf->Text(10, 32, 'банкоматах и мобильных приложениях');
        $fileName = tempnam(sys_get_temp_dir(), 'qr_code_');
        $code = new QrCodeModel;
        $code->fillDetailsGardient(1);
        if ($fio) {
            $code->fillDetailsUserInStead($stead_id);
        }
        $code->fillDetailsDevice(2, $stead_id);
        $code->getFile($fileName);
//        $this->createQrcode($gardient, $steadModel, $ReceiptTypeModels, $fileName);
        $pdf->Image($fileName, 10, 36, 40, 40, 'PNG', '', '', true, 300, '', false, false, 1, false, false, false);
    }

    public static function createClearReceipt($pdf)
    {
        $pdf->setFontStretching(105);
        $pdf->SetFont('freesans', 'B', 9);
        $pdf->Text(20, 22, 'Извещение');
        $pdf->Text(23, 81, 'Кассир');
        $pdf->Text(20, 142, 'Квитанция');
        $pdf->Text(23, 151, 'Кассир');

        $pdf->SetFont('freesans', 'I', 5);
        $pdf->Text(178.5, 23, 'Форма № ПД-4');

        $pdf->SetDrawColor(0);
        $pdf->SetLineWidth(0.3);
        $pdf->Line(9, 20, 197, 20);
        $pdf->Line(197, 20, 197, 160);
        $pdf->Line(9, 20, 9, 160);
        $pdf->Line(9, 160, 197, 160);
        $pdf->Line(9, 90, 197, 90);
        $pdf->Line(50.7, 20, 50.7, 160);


//для двух проходов: нижнего и верхнего
        $s_arr = [-0.5, 70];
        foreach ($s_arr as $s) {
            //Линии инн
            $pdf->Line(55, $s + 32, 192, $s + 32);
            $pdf->Line(55, $s + 35, 103, $s + 35);
            $pdf->Line(55, $s + 39, 103, $s + 39);

            $a = 55;
            for ($i = 0; $i < 13; $i++) {
                $pdf->Line($a, $s + 35, $a, $s + 39);
                $a = $a + 4;
            }

            $pdf->Line(112, $s + 35, 192, $s + 35);
            $pdf->Line(112, $s + 39, 192, $s + 39);

            $a = 192;
            for ($i = 0; $i < 21; $i++) {
                $pdf->Line($a, $s + 35, $a, $s + 39);
                $a = $a - 4;
            }

            $pdf->Line(156, $s + 42, 192, $s + 42);
            $pdf->Line(156, $s + 46, 192, $s + 46);
            $pdf->Line(60, $s + 46, 144, $s + 46);

            $a = 192;
            for ($i = 0; $i < 10; $i++) {
                $pdf->Line($a, $s + 42, $a, $s + 46);
                $a = $a - 4;
            }
            // кор счет
            $pdf->Line(112, $s + 47, 192, $s + 47);
            $pdf->Line(112, $s + 51, 192, $s + 51);

            $a = 192;
            for ($i = 0; $i < 21; $i++) {
                $pdf->Line($a, $s + 47, $a, $s + 51);
                $a = $a - 4;
            }

            $pdf->Line(55, $s + 55, 100, $s + 55);
            //$pdf->Line(136, $s + 55, 192, $s + 55);

            // fio и назначение
            $pdf->Line(88, $s + 62, 192, $s + 62);
            $pdf->Line(88, $s + 67, 192, $s + 67);
            $pdf->Line(55, $s + 72, 192, $s + 72);
            // сумма платежа
            $pdf->Line(80, $s + 78, 95, $s + 78);
            $pdf->Line(103, $s + 78, 110, $s + 78);

//$pdf->Line(164,$s+73,173,$s+73);
//$pdf->Line(180,$s+73,185,$s+73);

//$pdf->Line(66,$s+78,81,$s+78);
//pdf->Line(89,$s+78,96,$s+78);
            $pdf->Line(140, $s + 78, 148, $s + 78);
            $pdf->Line(151, $s + 78, 180, $s + 78);
            $pdf->Line(186, $s + 78, 189, $s + 78);
            $pdf->Line(150, $s + 88.6, 192, $s + 88.6);

//ТЕКСТЫ
            $pdf->SetFont('freesans', '', 6);

            $pdf->Text(104, $s + 32, '(наименование получателя платежа)');

            $pdf->SetFont('freesans', '', 6);
            $pdf->Text(65, $s + 39, '(ИНН получателя платежа)');

            $pdf->Text(135, $s + 39, '(номер счета получателя платежа)');

            $pdf->SetFont('freesans', '', 8);

            $pdf->Text(148, $s + 42.5, 'БИК');

            $pdf->SetFont('freesans', '', 7);
            $pdf->Text(55, $s + 47, 'Номер кор./сч.банка получателя платежа');

            $pdf->SetFont('freesans', 'B', 9);
//            $pdf->Text(75, $s+51,  $steadModel->number );

            $pdf->SetFont('freesans', '', 6);
            $pdf->Text(70, $s + 55, '(номер участка)');

            $pdf->SetFont('freesans', '', 8);
            $pdf->Text(55, $s + 59, 'Ф.И.О. Плательщика');

            $pdf->SetFont('freesans', '', 8);
            $pdf->Text(55, $s + 64, 'Назначение платежа');

            $pdf->SetFont('freesans', '', 8);
            $pdf->Text(55, $s + 75, 'Сумма платежа');
            $pdf->Text(96, $s + 75, 'руб.');
            $pdf->Text(110, $s + 75, 'коп.');

            //$pdf->Text(130, $s+70, 'Сумма платы за услуги' );

            // $pdf->Text(173, $s+70,  'руб.' );
            // $pdf->Text(185, $s+70,  'коп.' );
            // $pdf->Text(55, $s+75,  'Итого' );
            // $pdf->Text(82, $s+75,  'руб.' );
            // $pdf->Text(96, $s+75,  'коп.' );
            $pdf->Text(138, $s + 75, '"');
            $pdf->Text(147, $s + 75, '"');
//            $pdf->Text(180, $s + 75, '202');
            $pdf->Text(180, $s + 75, substr(date("Y"), 0, -1));
            $pdf->Text(189, $s + 75, 'г.');

            $pdf->SetFont('freesans', '', 6);
            $pdf->Text(55, $s + 80, 'С условиями приема указанной в платежном документе суммы, в т.ч. с суммой взимаемой платы за услуги');
            $pdf->Text(55, $s + 83, 'банка, ознакомлен и согласен');

            $pdf->SetFont('freesans', 'B', 7);
            $pdf->Text(119, $s + 85, 'Подпись плательщика');

//Заполняем данные предприятия

            $pdf->SetFont('freesans', '', 10);
//            $skip = 120-strlen($gardient->name)*0.5;
//            $pdf->Text($skip, $s + 28, $gardient->name);

// //Банк

            $pdf->SetFont('freesans', '', 7);
            $pdf->Text(55, $s + 42.5, 'в');
//            $pdf->Text(59, $s + 42.5, $gardient->BankName);

//Заполняем данные клиента
//            $summa_rub = "";
//            $summa_kop = "";

//            $pdf->SetFont('freesans', 'B', 10);

//ИНН получателя платежа (12-значный)

//            $a = 99;
//            $arr = str_split($gardient->PayeeINN);
//            $len= count($arr);
//            for ($i = 1; $i <= $len; $i++) {
//                $pdf->Text($a, $s + 34.8, $arr[$len-$i]);
//                $a = $a - 4;
//            }

//номер счета получателя платежа (20-значный)

//            $a = 112;
//            $arr = str_split($gardient->PersonalAcc);
//            for ($i = 0; $i < 20; $i++) {
//                $pdf->Text($a, $s + 34.8, $arr[$i]);
//                $a = $a + 4;
//            }

//БИК (9-значный)

//            $a = 156;
//            $arr = str_split($gardient->BIC);
//            for ($i = 0; $i < 9; $i++) {
//                $pdf->Text($a, $s + 42, $arr[$i]);
//                $a = $a + 4;
//            }

//Номер кор./сч.банка получателя платежа (20-значный)

//            $a = 112;
//            $arr = str_split($gardient->CorrespAcc);
//            for ($i = 0; $i < 20; $i++) {
//                $pdf->Text($a, $s + 46.7, $arr[$i]);
//                $a = $a + 4;
//            }

//            $pdf->SetFont('freesans', '', 10);

//            $pdf->Text(88, $s+58, $fio);

//            $pdf->Text(80, $s + 69, $summa_rub);
//            $pdf->Text(103.5, $s + 69, $summa_kop);
//            $discription = '';
//            $cash = 0;
//            mb_internal_encoding('UTF-8');
//            foreach ($ReceiptTypeModels->MeteringDevice as $MeteringDevice) {
//                $cash += $MeteringDevice->getTicket($steadModel->id);
//                $discription .= $MeteringDevice->name.' '.$MeteringDevice->cash_description . ' ';
//            }
//            $text = $ReceiptTypeModels->name.' '.$discription;
//            $pdf->Text(88, $s+63, mb_substr($text, 0, 55));
//            $pdf->Text(60, $s+68, mb_substr($text, 55));
//            $pdf->Text(82, $s + 74, floor($cash));
//            $pdf->Text(103.5, $s + 74, floor(fmod($cash,1)*100));
        }

//
//        $pdf->SetFont('freesans', '', 6);
//        $pdf->Text(15, 30, 'Код для оплаты в терминалах,');
//        $pdf->Text(10, 32, 'банкоматах и мобильных приложениях');
    }

//    public function createQrcode($gardient, $steadModel, $ReceiptTypeModels, $fileName)
//    {
//       $code = new QrCodeModel;
//       $code->fillDetailsGardient($gardient);
//       $code->fillDetailsUserInStead($steadModel);
//       $code->fillDetailsDevice($ReceiptTypeModels, $steadModel);
//       $code->getFile($fileName);
//    }


    public function renderPdf($id, $stead)
    {
        mb_internal_encoding('UTF-8');
        $steadModel = Stead::find((int)$stead);
        $gardient = $steadModel->gardient;
        $ReceiptType = ReceiptTypeModels::findOrFail((int)$id);
        $code = new QrCodeModel;
        $code->fillDetailsGardient($gardient);
        $code->fillDetailsUser($steadModel);
        $code->fillDetailsDevice($ReceiptType, $steadModel);
        // create new PDF document
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->AddPage();
        $pdf->setFontStretching(105);
        $pdf->SetFont('freesans', 'B', 9);
        $pdf->Text(20, 22, 'Извещение');
        $pdf->Text(23, 81, 'Кассир');
        $pdf->Text(20, 142, 'Квитанция');
        $pdf->Text(23, 151, 'Кассир');

//         $pdf->SetFont('freesans', 'B', 8);
//         $pdf->Text(54, 22, 'СБЕРБАНК РОССИИ');
//         $pdf->SetFont('freesans', '', 5);
//         $pdf->Text(54, 26, 'Основан в 1841 году' );
//          $pdf->Line(55,26,87,26);

        $pdf->SetFont('freesans', 'I', 5);
        $pdf->Text(178.5, 23, 'Форма № ПД-4');

        $pdf->SetDrawColor(0);
        $pdf->SetLineWidth(0.3);
        $pdf->Line(9, 20, 197, 20);
        $pdf->Line(197, 20, 197, 160);
        $pdf->Line(9, 20, 9, 160);
        $pdf->Line(9, 160, 197, 160);
        $pdf->Line(9, 90, 197, 90);
        $pdf->Line(50.7, 20, 50.7, 160);


//для двух проходов: нижнего и верхнего
        $s_arr = [-0.5, 70];
        foreach ($s_arr as $s) {
            //Линии инн
            $pdf->Line(55, $s + 32, 192, $s + 32);
            $pdf->Line(55, $s + 35, 103, $s + 35);
            $pdf->Line(55, $s + 39, 103, $s + 39);

            $a = 55;
            for ($i = 0; $i < 13; $i++) {
                $pdf->Line($a, $s + 35, $a, $s + 39);
                $a = $a + 4;
            }

            $pdf->Line(112, $s + 35, 192, $s + 35);
            $pdf->Line(112, $s + 39, 192, $s + 39);

            $a = 192;
            for ($i = 0; $i < 21; $i++) {
                $pdf->Line($a, $s + 35, $a, $s + 39);
                $a = $a - 4;
            }

            $pdf->Line(156, $s + 42, 192, $s + 42);
            $pdf->Line(156, $s + 46, 192, $s + 46);
            $pdf->Line(60, $s + 46, 144, $s + 46);

            $a = 192;
            for ($i = 0; $i < 10; $i++) {
                $pdf->Line($a, $s + 42, $a, $s + 46);
                $a = $a - 4;
            }
            // кор счет
            $pdf->Line(112, $s + 47, 192, $s + 47);
            $pdf->Line(112, $s + 51, 192, $s + 51);

            $a = 192;
            for ($i = 0; $i < 21; $i++) {
                $pdf->Line($a, $s + 47, $a, $s + 51);
                $a = $a - 4;
            }

            $pdf->Line(55, $s + 55, 100, $s + 55);
            //$pdf->Line(136, $s + 55, 192, $s + 55);

            // fio и назначение
            $pdf->Line(88, $s + 62, 192, $s + 62);
            $pdf->Line(88, $s + 67, 192, $s + 67);
            $pdf->Line(55, $s + 72, 192, $s + 72);
            // сумма платежа
            $pdf->Line(80, $s + 78, 95, $s + 78);
            $pdf->Line(103, $s + 78, 110, $s + 78);

//$pdf->Line(164,$s+73,173,$s+73);
//$pdf->Line(180,$s+73,185,$s+73);

//$pdf->Line(66,$s+78,81,$s+78);
//pdf->Line(89,$s+78,96,$s+78);
            $pdf->Line(140, $s + 78, 148, $s + 78);
            $pdf->Line(151, $s + 78, 180, $s + 78);
            $pdf->Line(186, $s + 78, 189, $s + 78);
            $pdf->Line(150, $s + 88.6, 192, $s + 88.6);

//ТЕКСТЫ
            $pdf->SetFont('freesans', '', 6);

            $pdf->Text(104, $s + 32, '(наименование получателя платежа)');

            $pdf->SetFont('freesans', '', 6);
            $pdf->Text(65, $s + 39, '(ИНН получателя платежа)');

            $pdf->Text(135, $s + 39, '(номер счета получателя платежа)');

            $pdf->SetFont('freesans', '', 8);

            $pdf->Text(148, $s + 42.5, 'БИК');

            $pdf->SetFont('freesans', '', 7);
            $pdf->Text(55, $s + 47, 'Номер кор./сч.банка получателя платежа');

            $pdf->SetFont('freesans', 'B', 9);
            $pdf->Text(75, $s + 51, $steadModel->number);
            //$pdf->Text(155, $s+51,  $stead->number );

            $pdf->SetFont('freesans', '', 6);
            $pdf->Text(70, $s + 55, '(номер участка)');
            //$pdf->Text(151, $s + 55, '(номер участка)');

            $pdf->SetFont('freesans', '', 8);
            $pdf->Text(55, $s + 59, 'Ф.И.О. Плательщика');

            $pdf->SetFont('freesans', '', 8);
            $pdf->Text(55, $s + 64, 'Назначение платежа');

            $pdf->SetFont('freesans', '', 8);
            $pdf->Text(55, $s + 75, 'Сумма платежа');
            $pdf->Text(96, $s + 75, 'руб.');
            $pdf->Text(110, $s + 75, 'коп.');

            //$pdf->Text(130, $s+70, 'Сумма платы за услуги' );

            // $pdf->Text(173, $s+70,  'руб.' );
            // $pdf->Text(185, $s+70,  'коп.' );
            // $pdf->Text(55, $s+75,  'Итого' );
            // $pdf->Text(82, $s+75,  'руб.' );
            // $pdf->Text(96, $s+75,  'коп.' );
            $pdf->Text(138, $s + 75, '"');
            $pdf->Text(147, $s + 75, '"');
            $pdf->Text(180, $s + 75, '201');
            $pdf->Text(189, $s + 75, 'г.');

            $pdf->SetFont('freesans', '', 6);
            $pdf->Text(55, $s + 80, 'С условиями приема указанной в платежном документе суммы, в т.ч. с суммой взимаемой платы за услуги');
            $pdf->Text(55, $s + 83, 'банка, ознакомлен и согласен');

            $pdf->SetFont('freesans', 'B', 7);
            $pdf->Text(119, $s + 85, 'Подпись плательщика');

//Заполняем данные предприятия

            $pdf->SetFont('freesans', '', 10);
            $skip = 120 - strlen($gardient->name) * 0.5;
            $pdf->Text($skip, $s + 28, $gardient->name);

// //Банк

            $pdf->SetFont('freesans', '', 7);
            $pdf->Text(55, $s + 42.5, 'в');
            $pdf->Text(59, $s + 42.5, $gardient->BankName);

//Заполняем данные клиента
            $fio = '';
            //dump(session('surname'));
            $fio .= null !== session('surname') ? session('surname') : $steadModel->surname;
            $fio .= null !== session('name') ? ' ' . session('name') : ' ' . $steadModel->name;
            $fio .= null !== session('patronymic') ? ' ' . session('patronymic') : ' ' . $steadModel->patronymic;

            $summa_rub = "";

            $summa_kop = "";

            $id_order = 298777;

            $pdf->SetFont('freesans', 'B', 10);

//ИНН получателя платежа (12-значный)

            $a = 99;
            $arr = str_split($gardient->PayeeINN);
            $len = count($arr);
            for ($i = 1; $i <= $len; $i++) {
                $pdf->Text($a, $s + 34.8, $arr[$len - $i]);
                $a = $a - 4;
            }

//номер счета получателя платежа (20-значный)

            $a = 112;
            $arr = str_split($gardient->PersonalAcc);
            for ($i = 0; $i < 20; $i++) {
                $pdf->Text($a, $s + 34.8, $arr[$i]);
                $a = $a + 4;
            }

//БИК (9-значный)

            $a = 156;
            $arr = str_split($gardient->BIC);
            for ($i = 0; $i < 9; $i++) {
                $pdf->Text($a, $s + 42, $arr[$i]);
                $a = $a + 4;
            }

//Номер кор./сч.банка получателя платежа (20-значный)

            $a = 112;
            $arr = str_split($gardient->CorrespAcc);
            for ($i = 0; $i < 20; $i++) {
                $pdf->Text($a, $s + 46.7, $arr[$i]);
                $a = $a + 4;
            }

            $pdf->SetFont('freesans', '', 10);

            $pdf->Text(88, $s + 58, $fio);

            $pdf->Text(80, $s + 69, $summa_rub);
            $pdf->Text(103.5, $s + 69, $summa_kop);
            $description = '';
            $cash = 0;
            mb_internal_encoding('UTF-8');
            foreach ($ReceiptType->MeteringDevice as $MeteringDevice) {
                $cash += $MeteringDevice->getTicket($steadModel->id);
                $description .= $MeteringDevice->name . ' ' . $MeteringDevice->cash_description . ' ';
            }
            $text = $ReceiptType->name . ' ' . $description;
            $pdf->Text(88, $s + 63, mb_substr($text, 0, 55));
            $pdf->Text(60, $s + 68, mb_substr($text, 55));
            $pdf->Text(82, $s + 74, floor($cash));
            $pdf->Text(103.5, $s + 74, floor(fmod($cash, 1) * 100));
        }


        $pdf->SetFont('freesans', '', 6);
        $pdf->Text(15, 30, 'Код для оплаты в терминалах,');
        $pdf->Text(10, 32, 'банкоматах и мобильных приложениях');
        $fileName = '/tmp/qr_code_' . $steadModel->id . '_' . time();
        $code->getFile($fileName);

        $pdf->Image($fileName, 10, 36, 40, 40, 'PNG', '', '', true, 300, '', false, false, 1, false, false, false);

        $pdf->Output('Ticket_' . $steadModel->number . '.pdf', 'I');
    }

//    public function drawPdf()
//    {
//        $gardient = Gardening::find(1);
//        self:: ddrawPdf($gardient, 400, 'Галышев Алексей Александрович Младший сын', 'подарок подарокподарокподарокподарокподарокподарокподарокподарокподарок одарокподарокподарокподарокподарокподарокподарокподарокподарок', 10);
////        self:: ddrawPdf($gardient);
//    }

    public function clearReceipt()
    {
        $gardient = Gardening::find(1);
        self:: ddrawPdf($gardient);
    }


    public static function ddrawPdf(Gardening $gardient, $stead_number = '', $fio = '', $descript = '', $cash = false)
    {
        mb_internal_encoding('UTF-8');
        $code = new QrCodeModel;
        $code->fillDetailsGardient($gardient);
        $code->fillFioUser($fio);
        $code->setDescription($descript);
        $code->setCash((int)$cash * 100);
        $code->setSteadNumber($stead_number);
//        $code->fillDetailsDevice($ReceiptTypeModels, $steadModel);
        // create new PDF document
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->AddPage();
        $pdf->setFontStretching(105);
        $pdf->SetFont('freesans', 'B', 9);
        $pdf->Text(20, 22, 'Извещение');
        $pdf->Text(23, 81, 'Кассир');
        $pdf->Text(20, 142, 'Квитанция');
        $pdf->Text(23, 151, 'Кассир');

        $pdf->SetFont('freesans', 'I', 5);
        $pdf->Text(178.5, 23, 'Форма № ПД-4');

        $pdf->SetDrawColor(0);
        $pdf->SetLineWidth(0.3);
        $pdf->Line(9, 20, 197, 20);
        $pdf->Line(197, 20, 197, 160);
        $pdf->Line(9, 20, 9, 160);
        $pdf->Line(9, 160, 197, 160);
        $pdf->Line(9, 90, 197, 90);
        $pdf->Line(50.7, 20, 50.7, 160);


//для двух проходов: нижнего и верхнего
        $s_arr = [-0.5, 70];
        foreach ($s_arr as $s) {
            //Линии инн
            $pdf->Line(55, $s + 32, 192, $s + 32);
            $pdf->Line(55, $s + 35, 103, $s + 35);
            $pdf->Line(55, $s + 39, 103, $s + 39);

            $a = 55;
            for ($i = 0; $i < 13; $i++) {
                $pdf->Line($a, $s + 35, $a, $s + 39);
                $a = $a + 4;
            }

            $pdf->Line(112, $s + 35, 192, $s + 35);
            $pdf->Line(112, $s + 39, 192, $s + 39);

            $a = 192;
            for ($i = 0; $i < 21; $i++) {
                $pdf->Line($a, $s + 35, $a, $s + 39);
                $a = $a - 4;
            }

            $pdf->Line(156, $s + 42, 192, $s + 42);
            $pdf->Line(156, $s + 46, 192, $s + 46);
            $pdf->Line(60, $s + 46, 144, $s + 46);

            $a = 192;
            for ($i = 0; $i < 10; $i++) {
                $pdf->Line($a, $s + 42, $a, $s + 46);
                $a = $a - 4;
            }
            // кор счет
            $pdf->Line(112, $s + 47, 192, $s + 47);
            $pdf->Line(112, $s + 51, 192, $s + 51);

            $a = 192;
            for ($i = 0; $i < 21; $i++) {
                $pdf->Line($a, $s + 47, $a, $s + 51);
                $a = $a - 4;
            }

            $pdf->Line(55, $s + 55, 100, $s + 55);
            //$pdf->Line(136, $s + 55, 192, $s + 55);

            // fio и назначение
            $pdf->Line(88, $s + 62, 192, $s + 62);
            $pdf->Line(88, $s + 67, 192, $s + 67);
            $pdf->Line(55, $s + 72, 192, $s + 72);
            // сумма платежа
            $pdf->Line(80, $s + 78, 95, $s + 78);
            $pdf->Line(103, $s + 78, 110, $s + 78);

            $pdf->Line(140, $s + 78, 148, $s + 78);
            $pdf->Line(151, $s + 78, 180, $s + 78);
            $pdf->Line(186, $s + 78, 189, $s + 78);
            $pdf->Line(150, $s + 88.6, 192, $s + 88.6);

//ТЕКСТЫ
            $pdf->SetFont('freesans', '', 6);

            $pdf->Text(104, $s + 32, '(наименование получателя платежа)');

            $pdf->SetFont('freesans', '', 6);
            $pdf->Text(65, $s + 39, '(ИНН получателя платежа)');

            $pdf->Text(135, $s + 39, '(номер счета получателя платежа)');

            $pdf->SetFont('freesans', '', 8);

            $pdf->Text(148, $s + 42.5, 'БИК');

            $pdf->SetFont('freesans', '', 7);
            $pdf->Text(55, $s + 47, 'Номер кор./сч.банка получателя платежа');

            $pdf->SetFont('freesans', 'B', 9);
            $pdf->Text(75, $s + 51, $stead_number);  // todo номер участка

            $pdf->SetFont('freesans', '', 6);
            $pdf->Text(70, $s + 55, '(номер участка)');
            //$pdf->Text(151, $s + 55, '(номер участка)');

            $pdf->SetFont('freesans', '', 8);
            $pdf->Text(55, $s + 59, 'Ф.И.О. Плательщика');

            $pdf->SetFont('freesans', '', 8);
            $pdf->Text(55, $s + 64, 'Назначение платежа');

            $pdf->SetFont('freesans', '', 8);
            $pdf->Text(55, $s + 75, 'Сумма платежа');
            $pdf->Text(96, $s + 75, 'руб.');
            $pdf->Text(110, $s + 75, 'коп.');

            $pdf->Text(138, $s + 75, '"');
            $pdf->Text(147, $s + 75, '"');
            $year = substr(date("Y"), 0, 3);
            $pdf->Text(180, $s + 75, $year);
            $pdf->Text(189, $s + 75, 'г.');

            $pdf->SetFont('freesans', '', 6);
            $pdf->Text(55, $s + 80, 'С условиями приема указанной в платежном документе суммы, в т.ч. с суммой взимаемой платы за услуги');
            $pdf->Text(55, $s + 83, 'банка, ознакомлен и согласен');

            $pdf->SetFont('freesans', 'B', 7);
            $pdf->Text(119, $s + 85, 'Подпись плательщика');

//Заполняем данные предприятия

            $pdf->SetFont('freesans', '', 10);
            $skip = 120 - strlen($gardient->name) * 0.5;
            $pdf->Text($skip, $s + 28, $gardient->name); // название садоводства

// //Банк

            $pdf->SetFont('freesans', '', 7);
            $pdf->Text(55, $s + 42.5, 'в');
            $pdf->Text(59, $s + 42.5, $gardient->BankName);

            $pdf->SetFont('freesans', 'B', 10);

//ИНН получателя платежа (12-значный)

            $a = 99;
            $arr = str_split($gardient->PayeeINN);
            $len = count($arr);
            for ($i = 1; $i <= $len; $i++) {
                $pdf->Text($a, $s + 34.8, $arr[$len - $i]);
                $a = $a - 4;
            }

//номер счета получателя платежа (20-значный)

            $a = 112;
            $arr = str_split($gardient->PersonalAcc);
            for ($i = 0; $i < 20; $i++) {
                $pdf->Text($a, $s + 34.8, $arr[$i]);
                $a = $a + 4;
            }

//БИК (9-значный)

            $a = 156;
            $arr = str_split($gardient->BIC);
            for ($i = 0; $i < 9; $i++) {
                $pdf->Text($a, $s + 42, $arr[$i]);
                $a = $a + 4;
            }

//Номер кор./сч.банка получателя платежа (20-значный)

            $a = 112;
            $arr = str_split($gardient->CorrespAcc);
            for ($i = 0; $i < 20; $i++) {
                $pdf->Text($a, $s + 46.7, $arr[$i]);
                $a = $a + 4;
            }

            $pdf->SetFont('freesans', '', 10);

            $pdf->Text(88, $s + 58, $fio);
            mb_internal_encoding('UTF-8');
            $pdf->Text(88, $s + 63, mb_substr($descript, 0, 50));  // todo назначение платежа
            $pdf->Text(60, $s + 68, mb_substr($descript, 50, 65));
            if ($cash) {
                $pdf->Text(82, $s + 74, floor($cash)); // todo сумма платежа
                $pdf->Text(103.5, $s + 74, floor(fmod($cash, 1) * 100));
            }
        }


        $pdf->SetFont('freesans', '', 6);
        $pdf->Text(15, 30, 'Код для оплаты в терминалах,');
        $pdf->Text(10, 32, 'банкоматах и мобильных приложениях');
        $fileName = '/tmp/qr_code_clear';
        $code->getFile($fileName);

        $pdf->Image($fileName, 10, 36, 40, 40, 'PNG', '', '', true, 300, '', false, false, 1, false, false, false);

        $pdf->Output('Ticket_.pdf', 'I');
    }

    public function test()
    {
        set_time_limit(250);
        $steads = Stead::query()->limit(2000)->get();
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $steadModel = Stead::find(1);
        $gardient = $steadModel->gardient;
        $ReceiptType = ReceiptTypeModels::findOrFail(2);
        $pdf->AddPage();
        $pdf->setFontStretching(105);
        $pdf->SetFont('freesans', '', 9);
        //$pdf->Text(178.5, 23, 'Форма № ПД-4');
        $pdf->SetFillColor(224, 235, 255);
        $pdf->Cell(20, 6, 'Номер', 1, 0, 'C', 0);
        $pdf->Cell(30, 6, 'Размер, м.кв', 1, 0, 'C', 0);
        $pdf->Cell(60, 6, 'ФИО', 1, 0, 'C', 0);
//        $pdf->Cell(40, 6, 'Сумма', 1, 0, 'C', 0);
        $pdf->Ln();
        $fill = 1;
        foreach ($steads as $key => $value) {
//            $pdf->Cell(10, 6, $key, 'LR', 0, 'C', $fill);
            $pdf->Cell(20, 6, $value->number, 'LR', 0, 'C', $fill);
            $pdf->Cell(30, 6, $value->size, 'LR', 0, 'C', $fill);
            $fio = isset($value->descriptions['fio']) ? $value->descriptions['fio'] : '';
            $str = strpos($fio, "19");
            if ($str) {
                $fio = substr($fio, 0, $str);
            }
            $ar = explode(' ', $fio);
            $fio = $ar[0];
            if (count($ar) > 1) {
                $fio .= ' ' . $ar[1];
            }
            if (count($ar) > 2) {
                $fio .= ' ' . $ar[2];
            }
            $pdf->Cell(60, 6, $fio, 'LR', 0, 'C', $fill);
            $sum = 11.02 * $value->size + 1458;
            $cash = 0;
            foreach ($ReceiptType->MeteringDevice as $MeteringDevice) {
                $cash += $MeteringDevice->getTicket($value->id);
            }
//            $pdf->Cell(40, 6, $cash.' руб.', 'LR', 0, 'C', $fill);
            $pdf->Ln();
            $fill = !$fill;
        }


        foreach ($steads as $key => $steadModel) {
            $code = new QrCodeModel;
            $code->fillDetailsGardient($gardient);
            $code->fillDetailsUserInStead($steadModel);
            $code->fillDetailsDevice($ReceiptType, $steadModel);
            $pdf->AddPage();
            $pdf->setFontStretching(105);
            $pdf->SetFont('freesans', 'B', 9);
            $pdf->Text(20, 22, 'Извещение');
            $pdf->Text(23, 81, 'Кассир');
            $pdf->Text(20, 142, 'Квитанция');
            $pdf->Text(23, 151, 'Кассир');

//         $pdf->SetFont('freesans', 'B', 8);
//         $pdf->Text(54, 22, 'СБЕРБАНК РОССИИ');
//         $pdf->SetFont('freesans', '', 5);
//         $pdf->Text(54, 26, 'Основан в 1841 году' );
//          $pdf->Line(55,26,87,26);

            $pdf->SetFont('freesans', 'I', 5);
            $pdf->Text(178.5, 23, 'Форма № ПД-4');

            $pdf->SetDrawColor(0);
            $pdf->SetLineWidth(0.3);
            $pdf->Line(9, 20, 197, 20);
            $pdf->Line(197, 20, 197, 160);
            $pdf->Line(9, 20, 9, 160);
            $pdf->Line(9, 160, 197, 160);
            $pdf->Line(9, 90, 197, 90);
            $pdf->Line(50.7, 20, 50.7, 160);


//для двух проходов: нижнего и верхнего
            $s_arr = [-0.5, 70];
            foreach ($s_arr as $s) {
                //Линии инн
                $pdf->Line(55, $s + 32, 192, $s + 32);
                $pdf->Line(55, $s + 35, 103, $s + 35);
                $pdf->Line(55, $s + 39, 103, $s + 39);

                $a = 55;
                for ($i = 0; $i < 13; $i++) {
                    $pdf->Line($a, $s + 35, $a, $s + 39);
                    $a = $a + 4;
                }

                $pdf->Line(112, $s + 35, 192, $s + 35);
                $pdf->Line(112, $s + 39, 192, $s + 39);

                $a = 192;
                for ($i = 0; $i < 21; $i++) {
                    $pdf->Line($a, $s + 35, $a, $s + 39);
                    $a = $a - 4;
                }

                $pdf->Line(156, $s + 42, 192, $s + 42);
                $pdf->Line(156, $s + 46, 192, $s + 46);
                $pdf->Line(60, $s + 46, 144, $s + 46);

                $a = 192;
                for ($i = 0; $i < 10; $i++) {
                    $pdf->Line($a, $s + 42, $a, $s + 46);
                    $a = $a - 4;
                }
                // кор счет
                $pdf->Line(112, $s + 47, 192, $s + 47);
                $pdf->Line(112, $s + 51, 192, $s + 51);

                $a = 192;
                for ($i = 0; $i < 21; $i++) {
                    $pdf->Line($a, $s + 47, $a, $s + 51);
                    $a = $a - 4;
                }

                $pdf->Line(55, $s + 55, 100, $s + 55);
                //$pdf->Line(136, $s + 55, 192, $s + 55);

                // fio и назначение
                $pdf->Line(88, $s + 62, 192, $s + 62);
                $pdf->Line(88, $s + 67, 192, $s + 67);
                $pdf->Line(55, $s + 72, 192, $s + 72);
                // сумма платежа
                $pdf->Line(80, $s + 78, 95, $s + 78);
                $pdf->Line(103, $s + 78, 110, $s + 78);

//$pdf->Line(164,$s+73,173,$s+73);
//$pdf->Line(180,$s+73,185,$s+73);

//$pdf->Line(66,$s+78,81,$s+78);
//pdf->Line(89,$s+78,96,$s+78);
                $pdf->Line(140, $s + 78, 148, $s + 78);
                $pdf->Line(151, $s + 78, 180, $s + 78);
                $pdf->Line(186, $s + 78, 189, $s + 78);
                $pdf->Line(150, $s + 88.6, 192, $s + 88.6);

//ТЕКСТЫ
                $pdf->SetFont('freesans', '', 6);

                $pdf->Text(104, $s + 32, '(наименование получателя платежа)');

                $pdf->SetFont('freesans', '', 6);
                $pdf->Text(65, $s + 39, '(ИНН получателя платежа)');

                $pdf->Text(135, $s + 39, '(номер счета получателя платежа)');

                $pdf->SetFont('freesans', '', 8);

                $pdf->Text(148, $s + 42.5, 'БИК');

                $pdf->SetFont('freesans', '', 7);
                $pdf->Text(55, $s + 47, 'Номер кор./сч.банка получателя платежа');

                $pdf->SetFont('freesans', 'B', 9);
                $pdf->Text(75, $s + 51, $steadModel->number);
                //$pdf->Text(155, $s+51,  $stead->number );

                $pdf->SetFont('freesans', '', 6);
                $pdf->Text(70, $s + 55, '(номер участка)');
                //$pdf->Text(151, $s + 55, '(номер участка)');

                $pdf->SetFont('freesans', '', 8);
                $pdf->Text(55, $s + 59, 'Ф.И.О. Плательщика');

                $pdf->SetFont('freesans', '', 8);
                $pdf->Text(55, $s + 64, 'Назначение платежа');

                $pdf->SetFont('freesans', '', 8);
                $pdf->Text(55, $s + 75, 'Сумма платежа');
                $pdf->Text(96, $s + 75, 'руб.');
                $pdf->Text(110, $s + 75, 'коп.');

                //$pdf->Text(130, $s+70, 'Сумма платы за услуги' );

                // $pdf->Text(173, $s+70,  'руб.' );
                // $pdf->Text(185, $s+70,  'коп.' );
                // $pdf->Text(55, $s+75,  'Итого' );
                // $pdf->Text(82, $s+75,  'руб.' );
                // $pdf->Text(96, $s+75,  'коп.' );
                $pdf->Text(138, $s + 75, '"');
                $pdf->Text(147, $s + 75, '"');
                $pdf->Text(180, $s + 75, '202');
                $pdf->Text(189, $s + 75, 'г.');

                $pdf->SetFont('freesans', '', 6);
                $pdf->Text(55, $s + 80, 'С условиями приема указанной в платежном документе суммы, в т.ч. с суммой взимаемой платы за услуги');
                $pdf->Text(55, $s + 83, 'банка, ознакомлен и согласен');

                $pdf->SetFont('freesans', 'B', 7);
                $pdf->Text(119, $s + 85, 'Подпись плательщика');

//Заполняем данные предприятия

                $pdf->SetFont('freesans', '', 10);
                $skip = 120 - strlen($gardient->name) * 0.5;
                $pdf->Text($skip, $s + 28, $gardient->name);

// //Банк

                $pdf->SetFont('freesans', '', 7);
                $pdf->Text(55, $s + 42.5, 'в');
                $pdf->Text(59, $s + 42.5, $gardient->BankName);

//Заполняем данные клиента
                $fio = isset($steadModel->descriptions['fio']) ? $steadModel->descriptions['fio'] : '';
                $str = strpos($fio, "19");
                if ($str) {
                    $fio = substr($fio, 0, $str);
                }
                $ar = explode(' ', $fio);
                $fio = $ar[0];
                if (count($ar) > 1) {
                    $fio .= ' ' . $ar[1];
                }
                if (count($ar) > 2) {
                    $fio .= ' ' . $ar[2];
                }
                $summa_rub = "";

                $summa_kop = "";

                $id_order = 298777;

                $pdf->SetFont('freesans', 'B', 10);

//ИНН получателя платежа (12-значный)

                $a = 99;
                $arr = str_split($gardient->PayeeINN);
                $len = count($arr);
                for ($i = 1; $i <= $len; $i++) {
                    $pdf->Text($a, $s + 34.8, $arr[$len - $i]);
                    $a = $a - 4;
                }

//номер счета получателя платежа (20-значный)

                $a = 112;
                $arr = str_split($gardient->PersonalAcc);
                for ($i = 0; $i < 20; $i++) {
                    $pdf->Text($a, $s + 34.8, $arr[$i]);
                    $a = $a + 4;
                }

//БИК (9-значный)

                $a = 156;
                $arr = str_split($gardient->BIC);
                for ($i = 0; $i < 9; $i++) {
                    $pdf->Text($a, $s + 42, $arr[$i]);
                    $a = $a + 4;
                }

//Номер кор./сч.банка получателя платежа (20-значный)

                $a = 112;
                $arr = str_split($gardient->CorrespAcc);
                for ($i = 0; $i < 20; $i++) {
                    $pdf->Text($a, $s + 46.7, $arr[$i]);
                    $a = $a + 4;
                }

                $pdf->SetFont('freesans', '', 10);

                $pdf->Text(88, $s + 58, $fio);

                $pdf->Text(80, $s + 69, $summa_rub);
                $pdf->Text(103.5, $s + 69, $summa_kop);
                $description = '';
                $cash = 0;
                mb_internal_encoding('UTF-8');
                foreach ($ReceiptType->MeteringDevice as $MeteringDevice) {
                    $cash += $MeteringDevice->getTicket($steadModel->id);
                    $description .= $MeteringDevice->name . ' ' . $MeteringDevice->cash_description . ' ';
                }
                $text = $ReceiptType->name . ' ' . $description;
                $pdf->Text(88, $s + 63, mb_substr($text, 0, 55));
                $pdf->Text(60, $s + 68, mb_substr($text, 55));
                $pdf->Text(82, $s + 74, floor($cash));
                $pdf->Text(103.5, $s + 74, floor(fmod($cash, 1) * 100));
            }


            $pdf->SetFont('freesans', '', 6);
            $pdf->Text(15, 30, 'Код для оплаты в терминалах,');
            $pdf->Text(10, 32, 'банкоматах и мобильных приложениях');
            $fileName = '/tmp/qr_code_' . $steadModel->id . '_' . time();
            $code->getFile($fileName);

            $pdf->Image($fileName, 10, 36, 40, 40, 'PNG', '', '', true, 300, '', false, false, 1, false, false, false);
        }

        $pdf->Output('rr.pdf', 'I');
//        return $steads;

    }

}
