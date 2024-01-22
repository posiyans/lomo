<?php

namespace App\Http\Abstracts;


use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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
        $this->spreadsheet->getProperties()->setCreator('CNT CRM')
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
    public function setAutoFilter($sheet = false)
    {
        if (!$sheet) {
            $sheet = $this->spreadsheet->getActiveSheet();
        }
        $sheet->setAutoFilter(
            $sheet->calculateWorksheetDimension()
        );
    }

    /**
     * установить цвет для ячейки
     * @param $cells ячейка
     * @param string $color цвет
     */
    public function fontColor($cells, $color = \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKRED)
    {
//        $this->spreadsheet->getActiveSheet()->getStyle($cells)->getFill()
//            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
//            ->getStartColor()->setARGB($color);
        $this->spreadsheet->getActiveSheet()->getStyle($cells)
            ->getFont()
            ->getColor()
            ->setARGB($color);
    }

    /**
     * Устанолвить текст в ячейке по центру
     *
     * @param String $item имя ячейки или диапазон 'A:G'
     */
    public function setCenter(string $item, $sheet = false)
    {
        if (!$sheet) {
            $sheet = $this->spreadsheet->getActiveSheet();
        }
        $sheet->getStyle($item)->getAlignment()->setHorizontal('center');
    }


    /**
     * установить аторазмер для колонки или дипозона колонок
     * @param $item имя колонки 'A' , или масив где 1 элемент начало 2 конец ['A', 'G']
     */
    public function setAutoSize($item, $sheet = false)
    {
        if (!$sheet) {
            $sheet = $this->spreadsheet->getActiveSheet();
        }
        if (is_array($item)) {
            foreach (range($item[0], $item[1]) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }
        if (is_string($item)) {
            $sheet->getColumnDimension($item)->setAutoSize(true);
        }
    }

    /**
     * вернуть фаил пользователю
     *
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function output($fileName)
    {
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save($fileName);
    }
}
