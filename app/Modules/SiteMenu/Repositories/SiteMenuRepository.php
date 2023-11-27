<?php

namespace App\Modules\SiteMenu\Repositories;

use App\Modules\SiteMenu\Models\CategoryModel;
use App\Modules\SiteMenu\Models\SiteMenuModel;

class SiteMenuRepository
{

    public function byId($id)
    {
        $model = SiteMenuModel::where(['id' => $id])->first();
        if ($model) {
            return $model;
        }
        throw new \Exception('Раздел не найден');
    }


    public function menu($parent = null)
    {
        $query = SiteMenuModel::query()->where('parent', $parent ? $parent->id : null);
        $menu = $query->orderBy('sort', 'asc')->get();
        foreach ($menu as $item) {
            $item->parent_sort = $parent ? $parent->sort : null;
            $child = $this->menu($item);

            if (count($child) > 0) {
                $item->children = $child;
            }
        }
        return $menu;
    }

}