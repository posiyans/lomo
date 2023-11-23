<?php

namespace App\Modules\SiteMenu\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\SiteMenu\Actions\CreateSiteMenuAction;
use App\Modules\SiteMenu\Repositories\SiteMenuRepository;
use Illuminate\Http\Request;

/**
 * Добавить раздел меню сайта
 *
 */
class AddSiteMenuController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,site-menu-edit');
    }

    public function __invoke(Request $request)
    {
        $name = $request->label;
        $parent = $request->parent;
        $path = $request->path;
        $menu = (new CreateSiteMenuAction($name))->path($path);
        $icon = $request->get('icon', 'menu');
        if ($parent) {
            $paren_model = (new SiteMenuRepository())->byId($parent);
            $menu->parent($paren_model);
        }
        $menu->icon($icon);
        return $menu->run();
    }
}
