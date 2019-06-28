<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stead;
use App\Models\ReceiptType;
use App\Models\QrCodeModel;

class PdfController extends Controller
{
    //
    public function renderPdf($id, $stead)
    {
        mb_internal_encoding('UTF-8');
        $steadModel = Stead::find((int)$stead);
        $gardient = $steadModel->gardient;
        $ReceiptType = ReceiptType::findOrFail((int)$id);
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
            $pdf->Line(88,$s+67,192,$s+67);
            $pdf->Line(55,$s+72,192,$s+72);
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
            $pdf->Text(75, $s+51,  $steadModel->number );
            //$pdf->Text(155, $s+51,  $stead->number );

            $pdf->SetFont('freesans', '', 6);
            $pdf->Text(70, $s + 55, '(номер участка)');
            //$pdf->Text(151, $s + 55, '(номер участка)');

            $pdf->SetFont('freesans', '', 8);
            $pdf->Text(55, $s + 59, 'Ф.И.О. Плательщика');

            $pdf->SetFont('freesans', '', 8);
            $pdf->Text(55, $s+64,  'Назначение платежа' );

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
            $skip = 120-strlen($gardient->name)*0.5;
            $pdf->Text($skip, $s + 28, $gardient->name);

// //Банк

            $pdf->SetFont('freesans', '', 7);
            $pdf->Text(55, $s + 42.5, 'в');
            $pdf->Text(59, $s + 42.5, $gardient->BankName);

//Заполняем данные клиента
            $fio = '';
            //dump(session('surname'));
            $fio .= null !== session('surname') ? session('surname') : $steadModel->surname;
            $fio .= null !== session('name') ? ' ' . session('name') : ' ' .$steadModel->name;
            $fio .= null !== session('patronymic') ? ' ' . session('patronymic') : ' ' .$steadModel->patronymic;

            $summa_rub = "";

            $summa_kop = "";

            $id_order = 298777;

            $pdf->SetFont('freesans', 'B', 10);

//ИНН получателя платежа (12-значный)

            $a = 99;
            $arr = str_split($gardient->PayeeINN);
            $len= count($arr);
            for ($i = 1; $i <= $len; $i++) {
                $pdf->Text($a, $s + 34.8, $arr[$len-$i]);
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

            $pdf->Text(88, $s+58, $fio);

            $pdf->Text(80, $s + 69, $summa_rub);
            $pdf->Text(103.5, $s + 69, $summa_kop);
            $discription = '';
            $cash = 0;
            mb_internal_encoding('UTF-8');
            foreach ($ReceiptType->MeteringDevice as $MeteringDevice) {
                $cash += $MeteringDevice->getTicket($steadModel->id);
                $discription .= $MeteringDevice->name.' '.$MeteringDevice->cash_description . ' ';
            }
            $text = $ReceiptType->name.' '.$discription;
            $pdf->Text(88, $s+63, mb_substr($text, 0, 55));
            $pdf->Text(60, $s+68, mb_substr($text, 55));
            $pdf->Text(82, $s + 74, floor($cash));
            $pdf->Text(103.5, $s + 74, floor(fmod($cash,1)*100));
        }


        $pdf->SetFont('freesans', '', 6);
        $pdf->Text(15, 30, 'Код для оплаты в терминалах,');
        $pdf->Text(10, 32, 'банкоматах и мобильных приложениях');
        $fileName= '/tmp/qr_code_'.$steadModel->id.'_'.time();
        $code->getFile($fileName);

        $pdf->Image($fileName, 10, 36, 40, 40, 'PNG', '', '', true, 300, '', false, false, 1, false, false, false);

        $pdf->Output('Ticket_'.$steadModel->number.'.pdf', 'I');

    }
}
