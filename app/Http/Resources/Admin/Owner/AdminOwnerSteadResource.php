<?php

namespace App\Http\Resources\Admin\Owner;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminOwnerSteadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        $data = parent::toArray($request);
        $data = [];
        $data['id'] = $this->id;
        $data['owner_id'] = $this->owner->id;
        $data['owner_uid'] = $this->owner_uid;
        $data['owner_fullName'] = $this->ownerFullName();
        $data['proportion'] = $this->proportion;
        $data['stead_id'] = $this->stead_id;
        return $data;
    }
}
