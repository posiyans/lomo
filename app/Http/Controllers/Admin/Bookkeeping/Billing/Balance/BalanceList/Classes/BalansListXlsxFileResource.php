<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\BalanceList\Classes;


use App\Http\Abstracts\AbstaractXlsxFile;
use App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\Classes\InvoiceAndPaymentListInSheet;
use App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\Repository\BalanceForSteadRepository;
use App\Models\Stead;


class BalansListXlsxFileResource extends AbstaractXlsxFile
{

    /**
     * список счетов и платежей по участку в XLSX
     *
     * @return \Illuminate\Http\Response
     */
    public function render($items)
    {
        $sheet = $this->spreadsheet->getActiveSheet();
            $sheet->setTitle('Баланс');
        (new BalansListSheet())->fillSheet($items, $sheet);
        $steads = Stead::all();
        $index = 1;
        foreach ($steads as $stead) {
            $items = (new BalanceForSteadRepository())->getForStead($stead->id);
            $this->spreadsheet->createSheet();
            $this->spreadsheet->setActiveSheetIndex($index++);
            $sheet = $this->spreadsheet->getActiveSheet();
            $sheet->setTitle('Уч.' . str_replace('/', '-',$stead->number));
            (new InvoiceAndPaymentListInSheet())->createSheet($sheet, $items);
        }
        $this->spreadsheet->setActiveSheetIndex(0);
        return $this->output();
    }




}
