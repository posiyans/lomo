<?php

namespace App\Modules\SiteMenu\Actions;


use App\Modules\SiteMenu\Models\SiteMenuModel;

class CreateSiteMenuAction
{

    private $model;

    public function __construct($label)
    {
        $this->model = new SiteMenuModel();
        $this->model->label = $label;
    }

    public function parent(SiteMenuModel $parent)
    {
        $this->model->parent = $parent->id;
        return $this;
    }

    public function path($path)
    {
        $this->model->path = $path;
        return $this;
    }

    public function icon($icon)
    {
        $this->model->icon = $icon;
        return $this;
    }

    public function run()
    {
        $this->model->logAndSave('Добавление меню');
        return $this->model;
    }

}
