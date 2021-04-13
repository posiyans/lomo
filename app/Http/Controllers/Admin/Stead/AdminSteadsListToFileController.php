<?php

namespace App\Http\Controllers\Admin\Stead;

use App\Http\Controllers\Admin\AbstractAdminController;

use App\Http\Controllers\Admin\Stead\Repositories\SteadListRepository;
use App\Http\Controllers\Admin\Stead\Resources\AdminSteadListXlsxFileResource;
use App\Http\Resources\Admin\AdminSteadResource;
use App\Http\Controllers\Admin\Stead\Resources\AdminSteadListResource;
use App\Models\Stead;
use Illuminate\Http\Request;

class AdminSteadsListToFileController extends AbstractAdminController
{


    public function getXlsx(Request $request)
    {
        $find = $request->get('title', false);
        $steads = (new SteadListRepository())->setPainateLimit(false)->findSteads($find);
        return (new AdminSteadListXlsxFileResource())->render($steads);
        return [$steads];
    }

}
