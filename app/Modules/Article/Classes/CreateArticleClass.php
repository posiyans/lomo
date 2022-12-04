<?php
namespace App\Modules\Article\Classes;

use App\Modules\Article\Models\ArticleModel;
use Illuminate\Support\Facades\Auth;

class CreateArticleClass
{
    private $article;

    public function __construct(array $opt = [])
    {
        $this->article = New ArticleModel();
        $this->article->fill($opt);
        $this->article->user_id = Auth::id() ?? 1;
        $this->article->public = 0;
        $this->article->publish_time = date('Y-m-d H:i:s');

    }

    public function status(int $status)
    {
        $this->article->public = $status;
        return $this;
    }

    public function run() {
        if ($this->article->logAndSave('Создание новости')) {
            return $this->article;
        }
        throw new \Exception($this->article->errors);
    }


}