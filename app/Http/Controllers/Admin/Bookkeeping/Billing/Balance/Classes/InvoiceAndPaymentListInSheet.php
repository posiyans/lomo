<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\Classes;


use App\Http\Abstracts\AbstaractXlsxFile;
use App\Models\Receipt\ReceiptType;


class InvoiceAndPaymentListInSheet extends AbstaractXlsxFile
{

    protected $types;
    protected $types_name;
    protected $sheet;
    protected $max_col = 'A';



    public function createSheet($sheet, $items)
    {
        $this->sheet = $sheet;
        $this->createHeader();
        $this->fillLine($items);
        $this->setAutoSize(['B', 'G'], $this->sheet);
        $this->setCenter('A:C', $this->sheet);
        $this->setCenter('E:' . $this->max_col, $this->sheet);
        $this->setAutoFilter($this->sheet);
    }

    public function createHeader()
    {
        $row = 1;
        $this->sheet->setCellValue('A'.$row, '№');
        $this->sheet->setCellValue('B'.$row, 'Тип');
        $this->sheet->setCellValue('C'.$row, 'Дата');
        $this->sheet->setCellValue('D'.$row, 'Назначение');
        $types = ReceiptType::all();
        $col = 'D';
        foreach ($types  as $type) {
            $col++;
            $this->sheet->setCellValue($col.$row, $type->name);
        }
        $this->max_col = $col;
    }


    private function fillLine($items)
    {
        $row = 2;
        $this->types = ReceiptType::getReceiptTypeIds();
        foreach ($items as $item) {
            $this->fillRow($row, $item);
            $row++;
        }
    }

    public function fillRow($row, $item)
    {
        if ($item['type'] == 'payment') {
            $this->fillPaymentRow($row, $item['data']);
        }
        if ($item['type'] == 'invoice') {
            $this->fillInvoiceRow($row, $item['data']);
        }
    }

    private function fillPaymentRow($row, $item)
    {
        $this->sheet->setCellValue('A' . $row, $row - 1);
        $this->sheet->setCellValue('B' . $row, 'Платеж');
        $this->sheet->setCellValue('C' . $row, date('d-m-Y', strtotime($item['payment_date'])));
        $this->sheet->setCellValue('D' . $row, $item['raw_data'][4]);
        $col = 'D';
        foreach ($this->types  as $type) {
            $col++;
            if ($item['type'] == $type) {
                $this->sheet->setCellValue($col.$row, $item['price']);
            }
        }
    }

    private function fillInvoiceRow($row, $item)
    {
        $this->sheet->setCellValue('A' . $row, $row - 1);
        $this->sheet->setCellValue('B' . $row, 'Счет');
        $this->sheet->setCellValue('C' . $row, date('d-m-Y', strtotime($item['created_at'])));
        $this->sheet->setCellValue('D' . $row, $item['title']);
        $col = 'D';
        foreach ($this->types  as $type) {
            $col++;
            if ($item['type'] == $type) {
                $this->sheet->setCellValue($col.$row, -$item['price']);
            }
        }
    }

}
