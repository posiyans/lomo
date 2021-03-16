<?php

namespace App\Http\Controllers\Admin\Owner;

use App\Http\Controllers\Admin\Report\PdfController;
use App\Http\Controllers\Admin\Report\PrimaryPdfController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Owner\AdminOwnerResource;
use App\Models\Billing\BillingInvoice;
use App\Models\Owner\OwnerUserModel;
use App\Models\Receipt\ReceiptType;
use Illuminate\Http\Request;
use App\Models\Stead;
use App\Models\Receipt\InstrumentReadings;
use App\Models\Receipt\MeteringDevice;
use App\Models\Gardening;


class OwnerModelFieldsController extends Controller
{
//    protected $query;

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-to-personal');
//        $this->query = BillingInvoice::query();
    }


    public function getFields()
    {
        $owner = new OwnerUserModel();
        return ['status' => true, 'data' => $owner->fields];

    }

}
