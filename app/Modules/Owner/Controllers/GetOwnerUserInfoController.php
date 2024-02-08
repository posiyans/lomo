<?php

namespace App\Modules\Owner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Owner\Models\OwnerUserModel;
use App\Modules\Owner\Resources\OwnerUserAllFieldResource;

class GetOwnerUserInfoController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin|owner,owner-show|owner-edit');
    }

    public function __invoke(OwnerUserModel $owner)
    {
        $user = \Auth::user();
        if (!$user->ability(['superAdmin'], ['owner-show', 'owner-edit'])) {
            $owner = $user->owner;
        }
        $ownerData = new OwnerUserAllFieldResource($owner);
        $steads = $owner->steads->sortBy('stead.number', SORT_NATURAL)->map(function ($item) {
            return [
                'stead_id' => $item->id,
                'number' => $item->number,
                'proportion' => $item->pivot->proportion,
            ];
        })->values();
        return ['owner' => $ownerData, 'steads' => $steads, $owner->steads];
    }

}
