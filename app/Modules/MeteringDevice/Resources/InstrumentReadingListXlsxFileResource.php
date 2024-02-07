<?php

namespace App\Modules\MeteringDevice\Resources;


use App\Http\Abstracts\AbstaractXlsxFile;
use App\Modules\MeteringDevice\Repositories\PreviousReadingsModelRepository;
use App\Modules\Rate\Repositories\RateRepository;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;


class InstrumentReadingListXlsxFileResource extends AbstaractXlsxFile
{

    protected $spreadsheet;

    /**
     * Список показаний  XLSX формате
     *
     * @return \Illuminate\Http\Response
     */
    public function render($items, $file_name)
    {
        $this->createHeader();
        $this->fillLine($items);
        $this->setAutoSize(['A', 'K']);
        $this->setAutoFilter();
        $this->output($file_name);
    }


    public function createHeader()
    {
        $sheet = $this->spreadsheet->getActiveSheet()->setTitle('Показания');
        $row = 1;
        $i = 1;
        $sheet->setCellValue([$i++, $row], 'Дата');
        $sheet->setCellValue([$i++, $row], 'Участок');
        $sheet->setCellValue([$i++, $row], 'Тариф');
        $sheet->setCellValue([$i++, $row], 'Стоимсоть');
        $sheet->setCellValue([$i++, $row], 'Прибор');
        $sheet->setCellValue([$i++, $row], 'Показания');
        $sheet->setCellValue([$i++, $row], 'Предыдущее показание');
        $sheet->setCellValue([$i++, $row], 'Величина');
        $sheet->setCellValue([$i++, $row], 'К оплате');
        $sheet->setCellValue([$i++, $row], 'Счет');
        $sheet->setCellValue([$i++, $row], 'Платеж');
    }

    private function fillLine($items)
    {
        $row = 2;
        foreach ($items as $item) {
            $this->setRow($item, $row++);
        }
    }


    private function setRow($item, $row)
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $i = 0;
        $sheet->setCellValue([++$i, $row], $item->date);
        $sheet->setCellValue([++$i, $row], $item->metering_device->stead->number);
        $rate = RateRepository::for_instrument_reading($item);
        $rate_cell = $item->metering_device->rate_type->name;
        $rate_cell .= ' ' . $rate->description;
        $sheet->setCellValue([++$i, $row], $rate_cell);
        $sheet->setCellValue([++$i, $row], $rate->ratio_a);
        $device = $item->metering_device->options['device_brand'] ?? '';
        $device .= ' Sn: ' . $item->metering_device->options['serial_number'] ?? '';
        $sheet->setCellValue([++$i, $row], $device);
        $sheet->setCellValue([++$i, $row], $item->value);
        $sheet->setCellValue([++$i, $row], (new PreviousReadingsModelRepository($item))->value());
        $delta = (new PreviousReadingsModelRepository($item))->delta();
        $sheet->setCellValue([++$i, $row], $delta);
        $sheet->setCellValue([++$i, $row], $delta * $rate->ratio_a);
        if (!$item->payment_id) {
            $this->fontColor(Coordinate::stringFromColumnIndex($i) . $row);
        }
        $sheet->setCellValue([++$i, $row], $item->invoice_id ? 'Счет № ' . $item->invoice_id : '');
        $sheet->setCellValue([++$i, $row], $item->payment_id ? 'Платеж № ' . $item->payment_id : '');
    }


}
