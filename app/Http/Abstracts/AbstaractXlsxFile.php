<?php

namespace App\Http\Abstracts;


use App\Models\Receipt\ReceiptType;
use App\Models\Stead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

abstract class AbstaractXlsxFile extends Controller
{

    protected $spreadsheet;


    public function __construct()
    {
        $this->createXLSX();
    }

    /**
     * создаем пустой фаил и запоняем его служебной информацией
     */
    public function createXLSX()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->spreadsheet ->getProperties()->setCreator('snt site')
            ->setLastModifiedBy('posiyans')
            ->setTitle('https://github.com/posiyans/lomo')
            ->setSubject('https://github.com/posiyans/lomo')
            ->setDescription('https://github.com/posiyans/lomo')
            ->setKeywords('https://github.com/posiyans/lomo')
            ->setCategory('https://github.com/posiyans/lomo');
    }

    /**
     *установить автофильтр
     */
    public function setAutoFilter()
    {
        $this->spreadsheet->getActiveSheet()->setAutoFilter(
            $this->spreadsheet->getActiveSheet()
                ->calculateWorksheetDimension()
        );
    }
    /**
     * установить цвет для ячейки
     * @param $cells ячейка
     * @param string $color цвет
     */
    public function cellColor($cells, $color = 'F28A8C'){
        $this->spreadsheet->getActiveSheet()->getStyle($cells)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB($color);

    }

    /**
     * Устанолвить текст в ячейке по центру
     *
     * @param String $item имя ячейки или диапазон 'A:G'
     */
    public function setCenter(String $item)
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->getStyle($item)->getAlignment()->setHorizontal('center');
    }


    /**
     * установить аторазмер для колонки или дипозона колонок
     * @param $item имя колонки 'A' , или масив где 1 элемент начало 2 конец ['A', 'G']
     */
    public function setAutoSize($item)
    {
        if (is_array($item)) {
            foreach (range($item[0],$item[1]) as $col) {
                $this->spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            }
        }
        if (is_string($item)) {
            $this->spreadsheet->getActiveSheet()->getColumnDimension($item)->setAutoSize(true);
        }

    }

    /**
     * вернуть фаил пользователю
     *
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function output()
    {
        header('Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
