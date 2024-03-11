<?php

namespace App\Modules\Search\Actions;

use App\Modules\Article\Repositories\ArticleRepository;
use App\Modules\Search\Resources\ArticleResource;

class FindTextInArticle
{
    private $text;
    private $limit;

    public function __construct($text)
    {
        $this->text = $text;
        $this->limit = false;
    }

    public function paginate($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function run()
    {
        $articles = (new ArticleRepository())->search($this->text);
        if ($this->limit) {
            $articles = $articles->paginate($this->limit);
        } else {
            $articles = $articles->all();
        }
        return ArticleResource::collection($articles);
    }
}
