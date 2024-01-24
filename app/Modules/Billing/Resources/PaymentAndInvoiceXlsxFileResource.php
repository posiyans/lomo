<?php

namespace App\Modules\Billing\Resources;


use App\Http\Abstracts\AbstaractXlsxFile;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;


class PaymentAndInvoiceXlsxFileResource extends AbstaractXlsxFile
{

    protected $spreadsheet;


    public function render($items, $file_name)
    {
        $this->createHeader();
        $this->fillLine($items);
        $this->setAutoSize(['A', 'G']);
        $this->setAutoFilter();
        $this->output($file_name);
    }


    public function createHeader()
    {
        $sheet = $this->spreadsheet->getActiveSheet()->setTitle('Баланс');
        $row = 1;
        $i = 1;
        $sheet->setCellValue([$i++, $row], '№');
        $sheet->setCellValue([$i++, $row], 'Участок');
        $sheet->setCellValue([$i++, $row], 'Дата');
        $sheet->setCellValue([$i++, $row], 'Сумма');
        $sheet->setCellValue([$i++, $row], 'Назначение');
        $sheet->setCellValue([$i++, $row], 'Тип');
        $sheet->setCellValue([$i++, $row], 'Статус');
    }


    private function fillLine($items)
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $row = 2;
        foreach ($items as $item) {
            $this->set_line($sheet, $item, $row++);
        }
    }

    private function set_line($sheet, $item, $row)
    {
        $i = 0;
        $type = $item->type;
        $sheet->setCellValue([++$i, $row], $type['label'] . ' № ' . $item->id);
        $sheet->setCellValue([++$i, $row], $item->stead['number']);
        $sheet->setCellValue([++$i, $row], $item->date);
        $sheet->setCellValue([++$i, $row], $item->price);
        if (!$item->is_paid) {
            $this->fontColor(Coordinate::stringFromColumnIndex($i) . $row);
        }
        $sheet->setCellValue([++$i, $row], $item->title);
        $sheet->setCellValue([++$i, $row], $item->rate['name']);
        $text = '';
        if ($type['uid'] == 'invoice' && $item->is_paid) {
            foreach ($item->payments as $payment) {
                $text .= ' платеж № ' . $payment->id . ' от ' . $payment->payment_date . ' на сумму ' . $payment->price . ' руб.;';
            }
        }
        if ($type['uid'] == 'payment' && $item->is_paid && $item->invoice) {
            $text .= ' счет № ' . $item->invoice->id . ' от ' . $item->invoice->created_at . ' на сумму ' . $item->invoice->price . ' руб.;';
        }
        $sheet->setCellValue([++$i, $row], $item->is_paid ? 'Оплачен' . $text : 'Не оплачен');
        if (!$item->is_paid) {
            $this->fontColor(Coordinate::stringFromColumnIndex($i) . $row);
        }
    }
}
