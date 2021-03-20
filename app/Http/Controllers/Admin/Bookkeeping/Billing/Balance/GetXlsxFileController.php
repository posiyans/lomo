<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\Balance;

use App\Http\Resources\Admin\Bookkeeping\AdminBalansSteadResource;
use App\Http\Resources\Admin\Bookkeeping\AdminInvoiceResource;
use App\Http\Resources\Admin\Bookkeeping\AdminPaymentResource;
use App\Models\Billing\BillingInvoice;
use App\Models\Billing\BillingPayment;
use App\Models\Billing\BillingReestr;
use App\Models\Receipt\InstrumentReadings;
use App\Models\Laratrust\Permission;
use App\Models\Laratrust\Role;
use App\Models\Receipt\ReceiptType;
use App\Models\Stead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
        $zeroline = $request->get('zeroLine', 0);
        if ($request->find) {
            $this->query->where('number', 'like', "%$request->find%");
        }
        $steads = $this->query->get();

        if ($request->category || $request->payment) {
            $data = [];
            if ($request->category) {
                foreach ($steads as $stead) {
                    $balans = $stead->getBalans($request->get('receipt_type', false));
                    if ($request->category == 1 && $balans >= $zeroline) {
                        $data[] = $stead;
                    } else if ($request->category == 2 && $balans < $zeroline) {
                        $data[] = $stead;
                    }
                }
            }

        } else {
            $data = $steads;
        }
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
