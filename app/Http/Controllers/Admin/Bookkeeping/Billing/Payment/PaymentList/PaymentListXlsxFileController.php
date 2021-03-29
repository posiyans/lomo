<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\Payment\PaymentList;


use App\Models\Receipt\ReceiptType;
use App\Models\Stead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PaymentListXlsxFileController extends Controller
{

    protected $spreadsheet;
    /**
     * Добавить счета  группк показаний
     *
     * @return \Illuminate\Http\Response
     */
    public static function save($payments)
    {
        $xlsx = new self();
        $xlsx->createXLSX();
        $xlsx->generateALLPayment($payments);
        $xlsx->spreadsheet->getActiveSheet()->setAutoFilter(
            $xlsx->spreadsheet->getActiveSheet()
                ->calculateWorksheetDimension()
        );
        foreach (range('B','G') as $col) {
            $xlsx->spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        $xlsx->returnXLSX();
    }


    public function createXLSX()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->spreadsheet ->getProperties()->setCreator('Maarten Balliauw')
            ->setLastModifiedBy('posiyans')
            ->setTitle('https://github.com/posiyans/lomo')
            ->setSubject('https://github.com/posiyans/lomo')
            ->setDescription('https://github.com/posiyans/lomo')
            ->setKeywords('https://github.com/posiyans/lomo')
            ->setCategory('https://github.com/posiyans/lomo');
    }

    public function generateALLPayment($payments)
    {
        $sheet = $this->spreadsheet->getActiveSheet()->setTitle('Все платежи');
        $row = 1;
        $sheet->setCellValue('A'.$row, '№');
        $sheet->setCellValue('B'.$row, 'Время');
        $sheet->setCellValue('C'.$row, 'Сумма');
        $sheet->setCellValue('D'.$row, 'Участок');
        $sheet->setCellValue('E'.$row, 'Тип');
        $sheet->setCellValue('F'.$row, 'ФИО');
        $sheet->setCellValue('G'.$row, 'Назначение');
        $row = 2;
        foreach ($payments as $item) {
            $sheet->setCellValue('A'.$row, $item->id);
            $sheet->setCellValue('B'.$row, $item->payment_date);
            $sheet->setCellValue('C'.$row, $item->price);
            $type = $item->getType ? $item->getType->name : '';
            $stead = $item->stead ? $item->stead->number : '';
            if (!$stead) {
                $this->cellColor('D'.$row);
            }
            if (!$type) {
                $this->cellColor('E'.$row);
            }
            $sheet->setCellValue('D'.$row, $stead);
            $sheet->setCellValue('E'.$row, $type);
            $sheet->setCellValue('F'.$row, $item->raw_data[3]);
            $sheet->setCellValue('G'.$row, $item->raw_data[4]);
            $row++;
        }
    }

    public function cellColor($cells,$color = 'F28A8C'){
        $this->spreadsheet->getActiveSheet()->getStyle($cells)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB($color);

    }

    public function returnXLSX()
    {
//        $sheet = $this->spreadsheet->getActiveSheet()->setTitle('Платежи');
//        $row = 1;
//        $sheet->setCellValue('A'.$row, 'Участок');
//        $sheet->setCellValue('B'.$row, 'Размер');
//        $sheet->setCellValue('C'.$row, 'Баланс');
//        $col = 'D';
//        $types = ReceiptType::all();
//
//        foreach ($types  as $type) {
//            $sheet->setCellValue($col.$row, $type->name);
//            $col++;
//        }
//        $row = 2;
//        $types = ReceiptType::getReceiptTypeIds();
//        foreach ($data as $item) {
//
//            $sheet->setCellValue('A'.$row, $item->number);
//            $sheet->setCellValue('B'.$row, $item->size);
//            $sheet->setCellValue('C'.$row, round($item->getBalans(), 2));
//            $col = 'D';
//            foreach ($types  as $type) {
//                $temp = round($item->getBalans($type), 2);
//                $sheet->setCellValue($col.$row, $temp);
//                $col++;
//            }
//            $row++;
//        }


        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

}
