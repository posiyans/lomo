<?php

namespace App\Http\Controllers\Admin\Owner\Classes;

use App\Http\Controllers\Controller;
use App\Models\Owner\OwnerUserModel;
use App\Models\Owner\OwnerUserSteadModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class DeleteOwnerUserStead extends Controller
{


    /**
     * Удалить связь между собственником и участком
     *
     * @param OwnerUserModel $ownrer
     * @throws \Exception
     */
    public function deleteRelations(OwnerUserSteadModel $ownrer)
    {
        // todo записать кто удалил!!!
        if ($ownrer->delete()) {
          return true;
        }
        throw new \Exception('Ошибка удаления участка');

    }


}

