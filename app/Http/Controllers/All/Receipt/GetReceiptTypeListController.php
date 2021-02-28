<?php

namespace App\Http\Controllers\All\Receipt;

use App\Http\Controllers\Admin\Report\PdfController;
use App\Http\Controllers\Admin\Report\PrimaryPdfController;
use App\Http\Controllers\Controller;
use App\Models\Receipt\ReceiptType;
use Illuminate\Http\Request;
use App\Models\Stead;
use App\Models\Receipt\InstrumentReadings;
use App\Models\Receipt\MeteringDevice;
use App\Models\Gardening;


class GetReceiptTypeListController extends Controller
{



    public function index(Request $request)
    {
        $query = ReceiptType::query();
        $depends = $request->get('depends', false);
        if ($depends) {
            $query->where('depends', $depends);
        }
        $receipt = $query->get();
        return ['status' => true, 'data' => $receipt];
    }

}
