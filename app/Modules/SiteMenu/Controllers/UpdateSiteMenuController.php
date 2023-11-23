<?php

namespace App\Modules\SiteMenu\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\SiteMenu\Models\SiteMenuModel;
use App\Modules\SiteMenu\Repositories\SiteMenuRepository;
use Illuminate\Http\Request;

/**
 * Редактирование раздел меню сайта
 *
 */
class UpdateSiteMenuController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,site-menu-edit');
    }

    public function __invoke(SiteMenuModel $menu, Request $request)
    {
        $parent = $request->parent;
        $menu->fill($request->only(['label', 'icon', 'path']));
        if ($parent) {
            $paren_model = (new SiteMenuRepository())->byId($parent);
            $menu->parent = $paren_model->id;
        }
        $menu->logAndSave('Изменение меню');
        return $menu;
    }
}
