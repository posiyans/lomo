<?php

namespace App\Modules\Appeal\Resources;

class AppealFullResource extends AppealResource
{


    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['text'] = $this->text;
        return $data;
    }
}
