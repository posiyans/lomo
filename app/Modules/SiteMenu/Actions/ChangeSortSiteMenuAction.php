<?php

namespace App\Modules\SiteMenu\Actions;


use App\Modules\SiteMenu\Models\SiteMenuModel;

class ChangeSortSiteMenuAction
{

    private $model;
    private $all;

    public function __construct(SiteMenuModel $menu)
    {
        $this->model = $menu;
        $this->all = SiteMenuModel::where('parent', $this->model->parent)
            ->where('id', '!=', $this->model->id)
            ->orderBy('sort', 'ASC')
            ->get();
    }

    public function sort($sort)
    {
        if ($sort < 1) {
            $sort = 1;
        }
        if ($sort > count($this->all)) {
            $sort = count($this->all) + 1;
        }
        $this->model->sort = $sort;
        return $this;
    }


    public function run()
    {
        $this->model->logAndSave('Изменение сортировки');
        $this->changeSort();
        return $this->model;
    }

    private function changeSort()
    {
//        $menus = SiteMenuModel::where('parent', $this->model->parent)
//            ->where('id', '!=', $this->model->id)
//            ->orderBy('sort', 'ASC')
//            ->get();
        $i = 1;
        foreach ($this->all as $menu) {
            if ($i == $this->model->sort) {
                $i++;
            }
            $menu->sort = $i;
            $menu->save();
            $i++;
        }
    }

}
