<?php

namespace App\Modules\Article\Resources;

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
        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'title' => $this->title,
            'resume' => (new GetResumeForArticleRepository($this->resource))->run(),
            'category_id' => $this->category_id,
            'allow_comments' => $this->allow_comments,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
            'slug' => $this->slug,
        ];
    }
}
