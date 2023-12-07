<?php

namespace App\Modules\Owner\Actions;

use App\Modules\Owner\Repositories\OwnerUserRepository;
use Illuminate\Support\Facades\Cache;

/**
 * Провести сортировку списка собственников
 */
class CheckSortOwnerUserAction
{

    public static function sortByName()
    {
        $name = Cache::tags('owner_user')->get('ownerSort');
        if (!$name || $name != __METHOD__) {
            $owners = (new OwnerUserRepository())->run();
            $sort = [];
            foreach ($owners as $owner) {
                $sort[$owner->fullName()] = $owner;
            }
            ksort($sort);
            $i = 1;
            foreach ($sort as $item) {
                $item->sort = $i++;
                $item->save();
            }
        }
        Cache::tags('owner_user')->remember('ownerSort', 36000, function () {
            return __METHOD__;
        });
    }

}
