<?php

namespace App\Modules\Owner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Owner\Actions\CheckSortOwnerUserAction;
use App\Modules\Owner\Repositories\OwnerUserRepository;
use App\Modules\Owner\Resources\OwnerUserAndSteadsResource;
use Illuminate\Http\Request;

class GetOwnerUserListController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,owner-show|owner-edit');
    }

    public function __invoke(Request $request)
    {
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 20;
        $find = $request->find;
        CheckSortOwnerUserAction::sortByName();
        $owners = (new OwnerUserRepository())
            ->find($find)
            ->paginate($limit);

        return OwnerUserAndSteadsResource::collection($owners);
    }

}
