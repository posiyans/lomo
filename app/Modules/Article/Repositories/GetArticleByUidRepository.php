<?php

namespace App\Modules\Article\Repositories;

use App\Modules\Article\Models\ArticleModel;

class GetArticleByUidRepository
{

    private $query;

    public function __construct($uid)
    {
        $this->query = ArticleModel::where('uid', $uid);
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