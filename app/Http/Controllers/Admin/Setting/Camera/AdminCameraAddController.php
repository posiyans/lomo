<?php

namespace App\Http\Controllers\Admin\Setting\Camera;

use App\Models\Options\GlobalOptionModel;
use App\Models\Receipt\MeteringDevice;
use App\Models\Receipt\Rate;
use App\Models\Receipt\ReceiptType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\AbstractList;
use phpDocumentor\Reflection\Types\Mixed_;

class AdminCameraAddController extends Controller
{

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    public function index(Request $request)
    {
        $optionName = 'siteCameraSetting';
        $name = $request->post('name', false);
        $url = $request->post('url', false);
        $ttl = $request->post('ttl', 3600);
        if ($name && $url) {
           $opt =  GlobalOptionModel::addOption($optionName, ['name' => $name, 'url' => $url, 'ttl' => $ttl]);
           if ($opt) {
               return ['status' => true, 'data' => $opt];
           }
        }
        return ['status' => false];
    }

}
