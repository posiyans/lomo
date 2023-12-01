<?php

namespace App\Http\Controllers\All\Receipt;

use App\Http\Controllers\Controller;
use App\Models\Gardening;
use App\Modules\Receipt\Models\ReceiptTypeModels;
use Illuminate\Http\Request;


class GetReceiptTypeListController extends Controller
{


    public function index(Request $request)
    {
        $query = ReceiptTypeModels::query();
        $depends = $request->get('depends', false);
        if ($depends) {
            $query->where('depends', $depends);
        }
        $receipt = $query->get();
        return ['status' => true, 'data' => $receipt];
    }

}
