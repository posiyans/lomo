<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

abstract class AbstractAdminController extends Controller
{

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct()
    {
        $this->middleware('ability:superAdmin,access-admin-panel');
    }


}

