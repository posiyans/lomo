<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SteadResource extends JsonResource
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
            'id'=> $this->id,
            'number'=>$this->number,
            'size'=>$this->size,
            'user_fullName'=>optional($this->user)->fullName(),
            'user_fName'=>optional($this->user)->fName(),
            'user'=> $this->user ? $this->user : [],
            'discriptions' => $this->discriptions
        ];
    }
}
