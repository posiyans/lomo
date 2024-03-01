<?php

namespace App\Modules\Stead\Resources;

use App\Modules\Owner\Resources\OwnerUserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminSteadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $owners = $this->owners;
        $data = [];
        foreach ($owners as $owner) {
            $tmp = new OwnerUserResource($owner);
            $tmp = $tmp->resolve();
            $tmp['proportion'] = (int)$owner->pivot->proportion;
            $tmp['stead_id'] = $this->id;
            $tmp['user_uid'] = $owner->user_uid;
            $data[] = $tmp;
        }
        $rez = [
            'id' => $this->id,
            'number' => $this->number,
            'size' => $this->size,
            'owners' => $data,
        ];
        return array_merge($rez, $this->options);
    }
}
