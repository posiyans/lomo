<?php

namespace App\Http\Controllers\Admin\Owner\Resource;


use App\Http\Abstracts\AbstaractXlsxFile;


class OwnreListXlsxFileResource extends AbstaractXlsxFile
{

    protected $spreadsheet;
    /**
     * Добавить счета  группк показаний
     *
     * @return \Illuminate\Http\Response
     */
    public function render($owners)
    {
        $this->createHeader();
        $this->fillLine($owners);
        $this->setAutoSize(['B', 'G']);
        $this->setAutoFilter();
        return $this->output();
    }


    public function createHeader()
    {
        $sheet = $this->spreadsheet->getActiveSheet()->setTitle('Собственники');
        $row = 1;
        $sheet->setCellValue('A'.$row, '№');
        $sheet->setCellValue('B'.$row, 'ФИО');
        $sheet->setCellValue('C'.$row, 'Участок');
        $this->setCenter('C');
        $sheet->setCellValue('D'.$row, 'Тел.');
        $sheet->setCellValue('E'.$row, 'email');
        $sheet->setCellValue('F'.$row, 'Членство в СНТ');
        $sheet->setCellValue('G'.$row, 'Адрес');
    }


    private function fillLine($items)
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $row = 2;
        foreach ($items as $item) {
            $steads = $item->steads;
            $text = '';
            $stead_count = 0;
            foreach ($steads as $stead) {
                if (strlen($text) > 0 ) {
                   $text .= ',' . "\n";
                }
                $text .=  $stead->stead->number;
                if ($stead->proportion != 100) {
                    $text .= '('.$stead->proportion .'%) ';
                }
                $stead_count++;
            }
            if ($stead_count > 1) {
                $this->spreadsheet->getActiveSheet()->getRowDimension($row)->setRowHeight(14 * $stead_count);
            }

            $sheet->setCellValue('A'.$row, $row - 1);
            $sheet->setCellValue('B'.$row, $item->fullName());
            $sheet->setCellValue('C'.$row, $text);
            $sheet->setCellValue('D'.$row, $item->getValue('general_phone', ''));
            $sheet->setCellValue('E'.$row, $item->getValue('email', ''));
            $sheet->setCellValue('F'.$row, $item->getValue('member', ''));
            $sheet->setCellValue('G'.$row, $item->getValue('address', ''));
            $row++;
        }
    }



}
