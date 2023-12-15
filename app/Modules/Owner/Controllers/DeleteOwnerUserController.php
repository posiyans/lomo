<?php

namespace App\Modules\Owner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Owner\Actions\DeleteOwnerAction;
use App\Modules\Owner\Models\OwnerUserModel;

class DeleteOwnerUserController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,owner-edit');
    }

    public function __invoke(OwnerUserModel $owner)
    {
        try {
            (new DeleteOwnerAction($owner))->run();
            return ['status' => true];
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
