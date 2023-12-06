<?php

namespace App\Modules\Article\Resources;

use App\Modules\User\Resources\AdminUserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminArticleListResource extends JsonResource
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
            'category_id' => $this->category_id,
            'status' => $this->status,
            'allow_comments' => $this->allow_comments,
            'user' => (new AdminUserResource($this->user)),
            'updated_at' => $this->updated_at,
            'slug' => $this->slug,
//            'files' => FileResource::collection($this->files)
        ];
    }
}
