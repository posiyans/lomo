<?php

namespace App\Modules\Article\Resources;

use App\Modules\File\Resources\FileResource;
use App\Modules\User\Resources\AdminUserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminArticleResource extends JsonResource
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
            'resume' => $this->resume,
            'title' => $this->title,
            'text' => $this->text,
            'category_id' => $this->category_id,
            'status' => $this->status,
            'allow_comments' => $this->allow_comments,
            'author' => (new AdminUserResource($this->user)),
            'slug' => $this->slug,
            'updated_at' => $this->updated_at,
            'files' => FileResource::collection($this->files)
        ];
    }
}
