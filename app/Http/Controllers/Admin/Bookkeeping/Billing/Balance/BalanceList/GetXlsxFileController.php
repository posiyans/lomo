<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\BalanceList;


use App\Models\Receipt\ReceiptType;
use App\Models\Stead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GetXlsxFileController extends Controller
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
        $data = GetListController::getData($request);
        $this->generateXLSX($data);
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
        $sheet->setCellValue('A'.$row, 'Участок');
        $sheet->setCellValue('B'.$row, 'Размер');
        $sheet->setCellValue('C'.$row, 'Баланс');
        $col = 'D';
        $types = ReceiptType::all();

        foreach ($types  as $type) {
            $sheet->setCellValue($col.$row, $type->name);
            $col++;
        }
        $row = 2;
        $types = ReceiptType::getReceiptTypeIds();
        foreach ($data as $item) {

            $sheet->setCellValue('A'.$row, $item->number);
            $sheet->setCellValue('B'.$row, $item->size);
            $sheet->setCellValue('C'.$row, round($item->getBalans(), 2));
            $col = 'D';
            foreach ($types  as $type) {
                $temp = round($item->getBalans($type), 2);
                $sheet->setCellValue($col.$row, $temp);
                $col++;
            }
            $row++;
        }


        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

}
