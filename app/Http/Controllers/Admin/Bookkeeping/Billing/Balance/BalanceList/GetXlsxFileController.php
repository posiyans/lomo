<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\BalanceList;


use App\Http\Controllers\Admin\AbstractAdminController;
use App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\BalanceList\Classes\BalansListXlsxFileResource;
use App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\BalanceList\Classes\XlsxFileResource;
use App\Models\Stead;
use App\Modules\Receipt\Models\ReceiptTypeModels;
use Auth;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class GetXlsxFileController extends AbstractAdminController
{


    protected $query;


    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
        $this->query = Stead::query();
    }


    /**
     * Добавить счета  группк показаний
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = GetListController::getData($request);
        (new BalansListXlsxFileResource())->render($items);
//        $this->generateXLSX($items);
    }


    public function generateXLSX($data)
    {
        $spreadsheet = new Spreadsheet();

// Set document properties
        $spreadsheet->getProperties()->setCreator('Maarten Balliauw')
            ->setLastModifiedBy('posiyans')
            ->setTitle('https://github.com/posiyans/lomo')
            ->setSubject('https://github.com/posiyans/lomo')
            ->setDescription('https://github.com/posiyans/lomo')
            ->setKeywords('https://github.com/posiyans/lomo')
            ->setCategory('https://github.com/posiyans/lomo');
        $sheet = $spreadsheet->getActiveSheet()->setTitle('Simple');;
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
        $row = 2;
        $types = ReceiptTypeModels::getReceiptTypeIds();
        foreach ($data as $item) {
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


        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

}
