<?php

namespace App\Modules\Article\Repositories;

use App\Modules\Article\Models\ArticleModel;

class ArticleRepository
{

    private $query;

    public function __construct()
    {
        $this->query = ArticleModel::query();
    }

    public function slug($slug)
    {
        $this->query->where('slug', $slug);
        return $this;
    }

    public function public()
    {
        $this->query->where('public', 1);
        return $this;
    }

    public function run()
    {
        $model = $this->query->first();
        if ($model) {
            return $model;
        }
        throw new \Exception('Статья  не найдена');
    }


}