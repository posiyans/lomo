<?php

namespace App\Modules\SiteMenu\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\SiteMenu\Models\SiteMenuModel;

/**
 * Удалить раздел меню сайта
 *
 */
class DeleteSiteMenuController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,site-menu-edit');
    }

    public function __invoke(SiteMenuModel $menu)
    {
        $menu->delete();
        return true;
    }
}
