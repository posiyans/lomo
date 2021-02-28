<?php

namespace App\Http\Controllers\Admin\Report;

use App\Models\Gardening;
use App\Models\PrimaryQrCodeModel;
use Illuminate\Http\Request;
use App\Models\Stead;
use App\Models\Receipt\ReceiptType;
use App\Models\QrCodeModel;
use App\Http\Controllers\Controller;

class PrimaryPdfController extends Controller
{
    //
    protected $pdf;
    protected $page;
    protected $name;
    protected $inn;
    protected $personal_acc;
    protected $bic;
    protected $cor_acc;
    protected $qr_code = true;


    public function __construct()
    {
        mb_internal_encoding('UTF-8');
        $this->pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->page = [];
    }

    /**
     *  @param array $options
     *  'name' - наименование получателя
     *  'inn' - инн получателя
     *  'personal_acc' - номер счета получателя
     *  'bank_name' - название банка
     *  'bic' - БИК
     *  'cor_acc' - корюсчет банак получателя
     *
     *  'stead_num' - номер счета(учатска)
     *  'last_name' - фамилия плательщика
     *  'first_name' - имя плательщика
     *  'middle_name' - отчество плтельщика
     *  'purpose' - назначение платежа
     *  'sum' - сумма платежа
     */
    public function addPage(Array $options)
    {
        $this->page[] = $options;
    }

    /**
     * установить параметры садоводства
     *
     * @param $options
     *
     *  'name' - наименование получателя платежа
     *  'inn' - инн получателя
     *  'personal_acc' - номер счета получателя
     *  'bank_name' - название банка
     *  'bic' - БИК
     *  'cor_acc' - корюсчет банак получателя
     */
    public function setGlobalVar($options)
    {
        $this->name = isset($options['name']) ? $options['name'] : '';
        $this->inn = isset($options['inn']) ? $options['inn'] : '';
        $this->personal_acc = isset($options['personal_acc']) ? $options['personal_acc'] : '';
        $this->bic = isset($options['bic']) ? $options['bic'] : '';
        $this->bank_name = isset($options['bank_name']) ? $options['bank_name'] : '';
        $this->cor_acc = isset($options['cor_acc']) ? $options['cor_acc'] : '';

    }

    /**
     * не печатать QR код
     */
    public function qrCodeDisable()
    {
        $this->qr_code= false;
    }

    /**
     * созвать pdf
     */
    public function render()
    {
        foreach ($this->page as $item) {
            $this->pdf->AddPage();
            $this->createClearReceipt();
            $this->fillGadeningData();
            $this->fillUserData($item);
            if ($this->qr_code) {
                $this->insertQRcode($item);
            }
        }
    }

    /**
     * вернуть pdf
     *
     * @param $name
     */
    public function Output($name)
    {
        $this->pdf->Output($name, 'I');
    }


    public function fillUserData($data)
    {
        $fio = '';
        $fio .= isset($data['last_name']) ? $data['last_name'] . ' ' : '';
        $fio .= isset($data['first_name']) ? $data['first_name'] . ' ' : '';
        $fio .= isset($data['middle_name']) ? $data['middle_name'] . ' ' : '';
        $stead = isset($data['stead_num']) ? $data['stead_num'] : '';
        $purpose = isset($data['purpose']) ? $data['purpose'] : '';
        $cash = isset($data['sum']) ? $data['sum'] : false;
        $description = isset($data['description']) ? $data['description'] : false;
        if ($cash) {
            $kop = round(fmod($cash, 1) * 100);
            if ($kop < 10) {
                $kop = '0'.$kop;
            }
        }
        $s_arr = [-0.5, 70];
        foreach ($s_arr as $s) {
            $this->pdf->SetFont('freesans', '', 10);
            $this->pdf->Text(88, $s + 58, $fio);
            $this->pdf->SetFont('freesans', 'B', 10);
            $this->pdf->Text(75, $s + 51, $stead);

            $this->pdf->SetFont('freesans', '',9);
            if (strlen($purpose) > 100) {
                $t1 = substr($purpose, 0, strrpos(substr($purpose, 0, 110), ' '));
            } else {
               $t1 = $purpose;
            }
            $this->pdf->Text(88, $s + 63, $t1);
            $this->pdf->Text(55, $s + 68, mb_substr($purpose, strlen($t1)));
            if ($cash) {
                $this->pdf->Text(82, $s + 74, floor($cash));
                $this->pdf->Text(103.5, $s + 74, $kop);
            }
        }
        $this->addDescription($description);
    }


    /**
     * добавить снизу подробное описание платежа
     *
     * @param $desc
     */
    public function addDescription($desc)
    {
        if ($desc) {
            $this->pdf->SetFont('freesans', '',12);
            $lines = explode('@', $desc);
            $y = 170;
            $c= 1;
            foreach ($lines as $line) {
                if ($c == count($lines)) {
                    $this->pdf->SetFont('freesans', 'b',14);
                    $y += 2;
                }
                $this->pdf->Text(20, $y, $line);
                $y += 6;
                $c++;
            }
        }

    }


    public function insertQRcode($data)
    {
        $this->pdf->SetFont('freesans', '', 6);
        $this->pdf->Text(15, 30, 'Код для оплаты в терминалах,');
        $this->pdf->Text(10, 32, 'банкоматах и мобильных приложениях');
        $fileName= tempnam('/tmp', 'qr_code');;
        $code = new PrimaryQrCodeModel();
        $options = [
            'name' => $this->name,
            'inn' => $this->inn,
            'personal_acc' => $this->personal_acc,
            'bank_name' => $this->bank_name,
            'bic' => $this->bic,
            'cor_acc' => $this->cor_acc,
        ];
        $code->setDetailsGardient($options);
        $code-> setDetailsUser($data);
        $purpose = isset($data['purpose']) ? $data['purpose'] : false;
        $stead = isset($data['stead_num']) ? $data['stead_num'] : false;
        $cash = isset($data['sum']) ? $data['sum'] : 0;
        $code->setDescription($purpose);
        $code->setSteadNumber($stead);
        $code->setCash(floor($cash * 100));
        $code->getFile($fileName);
        $this->pdf->Image($fileName, 10, 36, 40, 40, 'PNG', '', '', true, 300, '', false, false, 1, false, false, false);
    }

    public function fillGadeningData()
    {
        $s_arr = [-0.5, 70];
        foreach ($s_arr as $s) {
            $this->pdf->SetFont('freesans', 'B', 10);
            $skip = 120 - strlen($this->name) * 0.5;
            $this->pdf->Text($skip, $s + 27.5, $this->name);
            $this->pdf->SetFont('freesans', '', 7);
            $this->pdf->Text(55, $s + 42.5, 'в');
            $this->pdf->Text(59, $s + 42.5, $this->bank_name);
            $this->pdf->SetFont('freesans', 'B', 10);
            //ИНН получателя платежа (12-значный)
            $a = 99;
            $arr = str_split($this->inn);
            $len= count($arr);
            for ($i = 1; $i <= $len; $i++) {
                $this->pdf->Text($a, $s + 34.8, $arr[$len-$i]);
                $a = $a - 4;
            }

//номер счета получателя платежа (20-значный)

            $a = 112;
            $arr = str_split($this->personal_acc);
            for ($i = 0; $i < 20; $i++) {
                $this->pdf->Text($a, $s + 34.8, $arr[$i]);
                $a = $a + 4;
            }

//БИК (9-значный)

            $a = 156;
            $arr = str_split($this->bic);
            for ($i = 0; $i < 9; $i++) {
                $this->pdf->Text($a, $s + 41.8, $arr[$i]);
                $a = $a + 4;
            }

//Номер кор./сч.банка получателя платежа (20-значный)

            $a = 112;
            $arr = str_split($this->cor_acc);
            for ($i = 0; $i < 20; $i++) {
                $this->pdf->Text($a, $s + 46.7, $arr[$i]);
                $a = $a + 4;
            }
        }
    }

    public function createClearReceipt()
    {
        $this->pdf->setFontStretching(105);
        $this->pdf->SetFont('freesans', 'B', 9);
        $this->pdf->Text(20, 22, 'Извещение');
        $this->pdf->Text(23, 81, 'Кассир');
        $this->pdf->Text(20, 142, 'Квитанция');
        $this->pdf->Text(23, 151, 'Кассир');

        $this->pdf->SetFont('freesans', 'I', 5);
        $this->pdf->Text(178.5, 23, 'Форма № ПД-4');

        $this->pdf->SetDrawColor(0);
        $this->pdf->SetLineWidth(0.3);
        $this->pdf->Line(9, 20, 197, 20);
        $this->pdf->Line(197, 20, 197, 160);
        $this->pdf->Line(9, 20, 9, 160);
        $this->pdf->Line(9, 160, 197, 160);
        $this->pdf->Line(9, 90, 197, 90);
        $this->pdf->Line(50.7, 20, 50.7, 160);

//для двух проходов: нижнего и верхнего
        $s_arr = [-0.5, 70];
        foreach ($s_arr as $s) {
            $this->pdf->SetLineWidth(0.3);
            //Линии инн
            $this->pdf->Line(55, $s + 32, 192, $s + 32);
            $this->pdf->Line(55, $s + 35, 103, $s + 35);
            $this->pdf->Line(55, $s + 39, 103, $s + 39);

            $a = 55;
            for ($i = 0; $i < 13; $i++) {
                $this->pdf->Line($a, $s + 35, $a, $s + 39);
                $a = $a + 4;
            }

            $this->pdf->Line(112, $s + 35, 192, $s + 35);
            $this->pdf->Line(112, $s + 39, 192, $s + 39);

            $a = 192;
            for ($i = 0; $i < 21; $i++) {
                $this->pdf->Line($a, $s + 35, $a, $s + 39);
                $a = $a - 4;
            }

            $this->pdf->Line(156, $s + 42, 192, $s + 42);
            $this->pdf->Line(156, $s + 46, 192, $s + 46);
            $this->pdf->Line(60, $s + 46, 144, $s + 46);

            $a = 192;
            for ($i = 0; $i < 10; $i++) {
                $this->pdf->Line($a, $s + 42, $a, $s + 46);
                $a = $a - 4;
            }
            // кор счет
            $this->pdf->Line(112, $s + 47, 192, $s + 47);
            $this->pdf->Line(112, $s + 51, 192, $s + 51);

            $a = 192;
            for ($i = 0; $i < 21; $i++) {
                $this->pdf->Line($a, $s + 47, $a, $s + 51);
                $a = $a - 4;
            }

            $this->pdf->Line(55, $s + 55, 100, $s + 55);

            // fio и назначение
            $this->pdf->Line(88, $s + 62, 192, $s + 62);
            $this->pdf->Line(88,$s+67,192,$s+67);
            $this->pdf->Line(55,$s+72,192,$s+72);
            // сумма платежа
            $this->pdf->Line(80, $s + 78, 95, $s + 78);
            $this->pdf->Line(103, $s + 78, 110, $s + 78);
            $this->pdf->Line(140, $s + 78, 148, $s + 78);
            $this->pdf->Line(151, $s + 78, 180, $s + 78);
            $this->pdf->Line(186, $s + 78, 189, $s + 78);
            $this->pdf->Line(150, $s + 88.6, 192, $s + 88.6);

//ТЕКСТЫ
            $this->pdf->SetFont('freesans', '', 6);
            $this->pdf->Text(104, $s + 32, '(наименование получателя платежа)');
            $this->pdf->SetFont('freesans', '', 6);
            $this->pdf->Text(65, $s + 39, '(ИНН получателя платежа)');
            $this->pdf->Text(135, $s + 39, '(номер счета получателя платежа)');
            $this->pdf->SetFont('freesans', '', 8);
            $this->pdf->Text(148, $s + 42.5, 'БИК');
            $this->pdf->SetFont('freesans', '', 7);
            $this->pdf->Text(55, $s + 47, 'Номер кор./сч.банка получателя платежа');
            $this->pdf->SetFont('freesans', 'B', 9);
            $this->pdf->SetFont('freesans', '', 6);
            $this->pdf->Text(70, $s + 55, '(номер участка)');
            $this->pdf->SetFont('freesans', '', 8);
            $this->pdf->Text(55, $s + 59, 'Ф.И.О. Плательщика');
            $this->pdf->SetFont('freesans', '', 8);
            $this->pdf->Text(55, $s+64,  'Назначение платежа' );
            $this->pdf->SetFont('freesans', '', 8);
            $this->pdf->Text(55, $s + 75, 'Сумма платежа');
            $this->pdf->Text(96, $s + 75, 'руб.');
            $this->pdf->Text(110, $s + 75, 'коп.');
            $this->pdf->Text(138, $s + 75, '"');
            $this->pdf->Text(147, $s + 75, '"');
            $this->pdf->Text(180, $s + 75, substr(date("Y"), 0, -1));
            $this->pdf->Text(189, $s + 75, 'г.');
            $this->pdf->SetFont('freesans', '', 6);
            $this->pdf->Text(55, $s + 80, 'С условиями приема указанной в платежном документе суммы, в т.ч. с суммой взимаемой платы за услуги');
            $this->pdf->Text(55, $s + 83, 'банка, ознакомлен и согласен');
            $this->pdf->SetFont('freesans', 'B', 7);
            $this->pdf->Text(119, $s + 85, 'Подпись плательщика');

//Заполняем данные предприятия
            $this->pdf->SetFont('freesans', '', 7);
            $this->pdf->Text(55, $s + 42.5, 'в');
        }
    }

}
