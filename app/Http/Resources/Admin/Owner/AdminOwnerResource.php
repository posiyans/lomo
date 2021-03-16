<?php

namespace App\Http\Resources\Admin\Owner;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminOwnerResource extends JsonResource
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
        $data['uid'] = $this->uid;
        $data['id'] = $this->id;
//        $data['fullName'] = $this->fullName();
        $steads = $this->steads;
        foreach ($steads as $stead) {
            $stead['number'] =  $stead->stead->number;
            unset($stead['stead']);
        }
        $data['steads'] = $this->steads;

        foreach (array_keys($this->fields) as $item) {
            $data[$item] = $this->getValue($item, '');
        }

        return $data;
    }
}
