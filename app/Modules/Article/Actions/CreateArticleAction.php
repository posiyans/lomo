<?php

namespace App\Modules\Article\Actions;

use App\Modules\Article\Models\ArticleModel;
use Illuminate\Support\Facades\Auth;

class CreateArticleAction
{
    private $article;

    public function __construct(array $opt = [])
    {
        $this->article = new ArticleModel();
        $this->article->status = 0;
        $this->article->user_id = Auth::id() ?? 0;
        $this->article->fill($opt);
    }

    public function status(int $status)
    {
        $this->article->status = $status;
        return $this;
    }

    public function run()
    {
        if ($this->article->logAndSave('Создание статьи')) {
            return $this->article;
        }
        throw new \Exception($this->article->errors);
    }


}