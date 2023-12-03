<?php

namespace App\Modules\Appeal\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppealTypeResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'label' => $this->label
        ];
    }
}
