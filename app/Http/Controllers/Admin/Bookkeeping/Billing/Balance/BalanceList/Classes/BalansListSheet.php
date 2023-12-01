<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\BalanceList\Classes;


use App\Http\Abstracts\AbstaractXlsxFile;
use App\Modules\Receipt\Models\ReceiptTypeModels;


class BalansListSheet extends AbstaractXlsxFile
{

    /**
     * формирование листа балансов по участкам
     *
     * @return \Illuminate\Http\Response
     */
    public function fillSheet($items, $sheet)
    {
        $this->fillHeader($sheet);
        $this->fillRow($items, $sheet);
    }

    private function fillHeader($sheet)
    {
        $row = 1;
        $sheet->setCellValue('A' . $row, 'Участок');
        $sheet->setCellValue('B' . $row, 'Размер');
        $sheet->setCellValue('C' . $row, 'Баланс');
        $col = 'D';
        $types = ReceiptTypeModels::all();

        foreach ($types as $type) {
            $sheet->setCellValue($col . $row, $type->name);
            $col++;
        }
    }

    private function fillRow($items, $sheet)
    {
        $row = 2;
        $types = ReceiptTypeModels::getReceiptTypeIds();
        foreach ($items as $item) {
            $sheet->setCellValue('A' . $row, $item->number);
            $sheet->setCellValue('B' . $row, $item->size);
            $sheet->setCellValue('C' . $row, round($item->getBalans(), 2));
            $col = 'D';
            foreach ($types as $type) {
                $temp = round($item->getBalans($type), 2);
                $sheet->setCellValue($col . $row, $temp);
                $col++;
            }
            $row++;
        }
    }


}
