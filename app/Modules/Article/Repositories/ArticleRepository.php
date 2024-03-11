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
        $this->query->where(function ($query) {
            $query->where('status', 2)->orWhere('status', 5);
        });
        return $this;
    }


    public function search($text)
    {
        if ($text) {
            $text = strtolower($text);
//            try {
//                $this->query->whereRaw('lower(concat_ws(" ",title,resume, text)) like ?', '"%' . $text . '%"');
//            } catch (\Exception $e) {
            $this->query->where(function ($subQuery) use ($text) {
                $subQuery->whereRaw('lower(title) like ?', '%' . $text . '%')
                    ->orwhereRaw('lower(resume) like ?', '%' . $text . '%')
                    ->orwhereRaw('lower(text) like ?', '%' . $text . '%');
            });
//            }
        }
//        dd([$this->query->toSql()]);
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

    public function all()
    {
        return $this->query->get();
    }

    public function paginate($limit)
    {
        return $this->query->paginate($limit);
    }


}