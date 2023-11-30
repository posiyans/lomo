<?php

namespace App\Modules\Owner\Resources;

use App\Modules\Owner\Repositories\OwnerFieldRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerUserAllFieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [];
        $data['uid'] = $this->uid;
        $data['id'] = $this->id;
        $data['user_uid'] = $this->user_uid;
        foreach ((new OwnerFieldRepository())->keys() as $item) {
            $data[$item] = $this->getValue($item, '');
        }


        return $data;
    }
}
