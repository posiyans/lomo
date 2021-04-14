<?php

namespace App\Http\Controllers\Admin\Stead\Resources;


use App\Http\Abstracts\AbstaractXlsxFile;


class AdminSteadListXlsxFileResource extends AbstaractXlsxFile
{

    protected $spreadsheet;
    /**
     * Список участков XLSX формате
     *
     * @return \Illuminate\Http\Response
     */
    public function render($steads)
    {
        $this->createHeader();
        $this->fillLine($steads);
        $this->setAutoSize(['C', 'E']);
        $this->setAutoFilter();
        return $this->output();
    }


    public function createHeader()
    {
        $sheet = $this->spreadsheet->getActiveSheet()->setTitle('Участки');
        $row = 1;
        $sheet->setCellValue('A'.$row, '№');
        $sheet->setCellValue('B'.$row, 'Участок');
        $sheet->setCellValue('C'.$row, 'Размер, кв.м');
        $sheet->setCellValue('D'.$row, 'Собственник');
        $sheet->setCellValue('E'.$row, 'Баланс');
        $this->setCenter('E');
    }


    private function fillLine($items)
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $row = 2;
        foreach ($items as $item) {
            $owners = '';
            $owner_count = 0;
            foreach ($item->owners as $owner) {
                if (strlen($owners) > 0 ) {
                    $owners .= ',' . "\n";
                }
                $owners .= $owner->owner->nameForMyRole();
                if ($owner->proportion != 100) {
                    $owners .= ' ('. $owner->proportion . '%)';
                }
                $owner_count++;
            }
            if ($owner_count > 1) {
                $this->spreadsheet->getActiveSheet()->getRowDimension($row)->setRowHeight(14 * $owner_count);
            }
            $sheet->setCellValue('A'.$row, $row - 1);
            $sheet->setCellValue('B'.$row, $item->number);
            $sheet->setCellValue('C'.$row, $item->size);
            $sheet->setCellValue('D'.$row, $owners);
            $balans = round($item->getBalans(), 2);
            $sheet->setCellValue('E'.$row, $balans);
            if ($balans < -1) {
               // $this->cellColor('E'.$row);
            }
            $row++;
        }
    }



}
