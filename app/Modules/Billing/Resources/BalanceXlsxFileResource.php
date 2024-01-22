<?php

namespace App\Modules\Billing\Resources;


use App\Http\Abstracts\AbstaractXlsxFile;
use App\Modules\Billing\Repositories\BalanceRepository;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * @refactory
 *
 */
class BalanceXlsxFileResource extends AbstaractXlsxFile
{

    protected $spreadsheet;

    private $header = [];

    private $rate_groups = [];


    public function setRateGroups($rate_group)
    {
        $this->rate_groups = $rate_group;
        return $this;
    }

    public function render($balances, $file_name)
    {
        $this->createHeader();
        $this->fillLine($balances);
        $this->setAutoSize(['A', 'D']);
        $this->setAutoFilter();
        $this->output($file_name);
    }


    public function createHeader()
    {
        $sheet = $this->spreadsheet->getActiveSheet()->setTitle('Баланс');
        $row = 1;
        $i = 1;
        $sheet->setCellValue([$i++, $row], 'Участок');
        $sheet->setCellValue([$i++, $row], 'Баланс');
        foreach ($this->rate_groups as $rate_group) {
            $sheet->setCellValue([$i++, $row], $rate_group->name);
        }
    }


    private function fillLine($balances)
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $row = 2;
        foreach ($balances as $item) {
            $this->set_line($sheet, $item, $row++);
        }
    }

    private function set_line($sheet, $item, $row)
    {
        $i = 0;
        $sheet->setCellValue([++$i, $row], $item->number ?? '');
        $sheet->setCellValue([++$i, $row], $item->price);
        if ($item->price < 0) {
            $this->fontColor(Coordinate::stringFromColumnIndex($i) . $row);
        }
        foreach ($this->rate_groups as $rate_group) {
            $price = (new BalanceRepository())
                ->forStead($item->id)
                ->forRateGroupId($rate_group->id)
                ->getPrice();
            $sheet->setCellValue([++$i, $row], $price);
            if ($price < 0) {
                $this->fontColor(Coordinate::stringFromColumnIndex($i) . $row);
            }
        }
    }
}
