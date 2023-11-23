<?php

namespace App\Modules\File\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
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
            'uid' => $this->uid,
            'name' => $this->name,
            'size' => $this->size,
            'type' => $this->type,
            'url' => '/api/v2/file/get?uid=' . $this->uid
        ];
    }
}
