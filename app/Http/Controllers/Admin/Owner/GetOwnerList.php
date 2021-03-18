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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;


class GetOwnerList
{
    public $status = true;
    public $error = false;
    public $rezult;
    public $total;
    public $offset;
    protected $data;

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct(Request $request)
    {
        $this->getAllOwner();
        $find_fio = $request->get('title', false);
        $this->findFio($find_fio);
        $this->total = count($this->data);
        $this->rezult = $this->paginate(array_values($this->data), $request->page, $request->limit);
    }


    public function getAllOwner()
    {
        $this->data = Cache::remember('allOwnerList', 600 ,function () {
            $data = [];
            $owners = OwnerUserModel::all();
            foreach ($owners as $owner) {
                $data[$owner->fullName()] = $owner;
            }
            ksort($data);
            return $data;
        });
    }


    public function findFio($find)
    {
        if ($find) {
            $find= explode(' ', mb_strtolower(str_replace('  ', ' ', trim($find))));
            $data = [];
            foreach ($this->data as $key => $value) {
                $f = true;
                foreach ($find as $item) {
                    if (!stristr(mb_strtolower($key), $item)) {
                       $f = false;
                    }
                }
                if ($f) {
                    $data[$key] = $value;
                }
            }
            $this->data = $data;
        }
    }

    public function paginate($array, $page = 1, $limit = 20)
    {
        if ($page < 1 ) {
            $page = 1;
        }
        $this->offset = ($page - 1) * $limit;
        return array_slice($array, $this->offset, $limit);
    }
}
