<?php

namespace App\Modules\Owner\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OwnerUserAndSteadsResource extends JsonResource
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
        $data['fullName'] = $this->nameForMyRole();
        $data['email'] = '';
        $data['general_phone'] = '';
        if (\Auth::user()->ability('superAdmin', 'owner-show')) {
            $data['email'] = $this->getValue('email', '');
            $data['general_phone'] = $this->getValue('general_phone', '');
        }
        $data['steads'] = $this->steads->sortBy('stead.number', SORT_NATURAL)->map(function ($item) {
            return [
                'stead_id' => $item->stead_id,
                'number' => $item->stead->number,
                'proportion' => $item->proportion,
            ];
        })->values();
        return $data;
    }
}
