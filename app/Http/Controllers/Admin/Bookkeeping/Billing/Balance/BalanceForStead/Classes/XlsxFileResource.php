<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\BalanceForStead\Classes;


use App\Http\Abstracts\AbstaractXlsxFile;
use App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\Classes\InvoiceAndPaymentListInSheet;


class XlsxFileResource extends AbstaractXlsxFile
{

    /**
     * список счетов и платежей по участку в XLSX
     *
     * @return \Illuminate\Http\Response
     */
    public function render($items, $number = false)
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        if ($number) {
            $sheet->setTitle('Уч. ' . $number);
        }
        (new InvoiceAndPaymentListInSheet())->createSheet($sheet, $items, $number);
//        $this->spreadsheet->createSheet();
        // $this->spreadsheet->setActiveSheetIndex(1);
        // $sheet = $this->spreadsheet->getActiveSheet()->setTitle('fhgdfhfghfgh');

        return $this->output();
    }




}
