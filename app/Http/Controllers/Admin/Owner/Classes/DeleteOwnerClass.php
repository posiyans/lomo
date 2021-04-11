<?php

namespace App\Http\Controllers\Admin\Owner\Classes;

use App\Http\Controllers\Controller;
use App\Models\Owner\OwnerUserModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class DeleteOwnerClass extends Controller
{


    /**
     * Удалить собственника
     *
     * @param OwnerUserModel $ownrer
     * @throws \Exception
     */
    public function deleteOwner(OwnerUserModel $ownrer)
    {
        $steads = $ownrer->steads;
        DB::beginTransaction();
        try {
            foreach ($steads as $stead) {
                $stead->delete();
            }
            $ownrer->delAllValue();
            $ownrer->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
        }
        return false;
    }


}

