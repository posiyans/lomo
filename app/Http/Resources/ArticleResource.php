<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'title' => $this->title,
            'resume' => $this->show_resume(),
            'category_id' => $this->category_id,
            'allow_comments' => $this->allow_comments,
            'public' => $this->public,
            'updated_at' => $this->updated_at,
        ];

    }
}
