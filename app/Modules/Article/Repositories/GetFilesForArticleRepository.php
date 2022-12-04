<?php
namespace App\Modules\Article\Repositories;

use App\Modules\Article\Models\ArticleModel;
use App\Modules\File\Repositories\GetFilesForObjectRepository;

class GetFilesForArticleRepository {

    private $article;

    public function __construct(ArticleModel $article)
    {
        $this->article = $article;
    }

    public function run()
    {
        return (new GetFilesForObjectRepository($this->article))->all();
    }


}