<?php

namespace App\Modules\Stead\Resources;

use App\Modules\File\Resources\FileResource;

class FileForSteadResource extends FileResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['description'] = $this->description;
        return $data;
    }
}
