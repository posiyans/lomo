<?php
namespace App\Modules\Article\Repositories;

use App\Modules\Article\Models\ArticleModel;

class GetArticleByUidRepository {

    private $uid;

    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    public function run()
    {
        $model = ArticleModel::where('uid', $this->uid)->first();
        if ($model) {
            return $model;
        }
        throw new \Exception('Статья  не найдена');
    }


}