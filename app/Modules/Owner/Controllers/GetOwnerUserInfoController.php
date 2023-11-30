<?php

namespace App\Modules\Owner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Owner\Models\OwnerUserModel;
use App\Modules\Owner\Resources\OwnerUserAllFieldResource;

class GetOwnerUserInfoController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,owner-show|owner-edit');
    }

    public function __invoke(OwnerUserModel $owner)
    {
        $ownerData = new OwnerUserAllFieldResource($owner);
        $steads = $owner->steads->sortBy('stead.number', SORT_NATURAL)->map(function ($item) {
            return [
                'stead_id' => $item->stead_id,
                'number' => $item->stead->number,
                'proportion' => $item->proportion,
            ];
        })->values();
        return ['owner' => $ownerData, 'steads' => $steads];
    }

}
