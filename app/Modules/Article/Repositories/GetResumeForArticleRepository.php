<?php

namespace App\Modules\Article\Repositories;

use App\Modules\Article\Models\ArticleModel;

class GetResumeForArticleRepository
{

    private $article;

    public function __construct(ArticleModel $article)
    {
        $this->article = $article;
    }

    public function run()
    {
        if ($this->article->resume) {
            return $this->article->resume;
        }
        $text = explode('</p>', $this->article->text);
        return $text[0] . '</p>';
    }


}