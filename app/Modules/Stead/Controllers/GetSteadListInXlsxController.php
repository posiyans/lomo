<?php

namespace App\Modules\Stead\Controllers;

use App\Http\Controllers\Admin\Stead\Resources\AdminSteadListXlsxFileResource;
use App\Http\Controllers\Controller;
use App\Modules\Stead\Repositories\SteadRepository;
use App\Modules\Stead\Validators\GetSteadListValidator;


class GetSteadListInXlsxController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,owner-show|owner-edit');
    }

    public function __invoke(GetSteadListValidator $request)
    {
        $steads = (new SteadRepository())->findByNumber($request->find)->run();
        $tmpfname = tempnam("/tmp", "owners");
        (new AdminSteadListXlsxFileResource())->render($steads, $tmpfname);
        return response()->download($tmpfname, 'Список_участков_' . date("Y-m-d_H-i-s") . '.xlsx');
    }


}
