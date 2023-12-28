<?php

namespace App\Modules\Owner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Owner\Repositories\OwnerUserRepository;
use App\Modules\Owner\Resources\OwnreListXlsxFileResource;
use Illuminate\Http\Request;

/**
 * получение списка собственников в xlsx
 */
class GetOwnerUserListInXlsxController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,owner-show|owner-edit');
    }

    public function __invoke(Request $request)
    {
        $find = $request->find;
        $owners = (new OwnerUserRepository())
            ->find($find)
            ->run();

        $tmpfname = tempnam(sys_get_temp_dir(), "owners");
//        return $tmpfname;
        (new OwnreListXlsxFileResource())->render($owners, $tmpfname);
        return response()->download($tmpfname, 'Список' . date("Y-m-d_H-i-s") . '.xlsx');
    }

}
