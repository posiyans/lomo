<?php

namespace App\Modules\Owner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Owner\Models\OwnerUserModel;
use App\Modules\Owner\Validators\UpdateOwnerUserFieldValidator;

/**
 * Обновить данные собственника
 */
class UpdateOwnerUserFieldController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,owner-edit');
    }

    public function __invoke(OwnerUserModel $owner, UpdateOwnerUserFieldValidator $request)
    {
        try {
            $value = $owner->setValue($request->field, $request->value);
            return response(['status' => true, 'data' => $value]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 450);
        }
    }

}
