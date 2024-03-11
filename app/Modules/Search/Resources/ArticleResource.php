<?php

namespace App\Modules\Search\Resources;

use App\Modules\Article\Repositories\GetResumeForArticleRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'title' => $this->title,
            'text' => (new GetResumeForArticleRepository($this->resource))->run(),
            'url' => '/article/show/' . $this->slug,
        ];
        return $data;
    }
}
