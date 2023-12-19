<?php

namespace App\Modules\Appeal\Resources;

use App\Modules\File\Resources\FileResource;

class AppealFullResource extends AppealResource
{


    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['text'] = $this->text;
        $data['files'] = FileResource::collection($this->files);
        return $data;
    }
}
