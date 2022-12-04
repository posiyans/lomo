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
            'id' => $this->id,
            'uid' => $this->uid,
            'name' => $this->name,
            'size' => $this->size,
            'url' => '/api/v2/file/get?uid=' , $this->uid

        ];

    }
}
