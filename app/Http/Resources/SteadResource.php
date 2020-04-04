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
//        $this->userFullName();
//        $this->sdfsdf = 'fgdfgdfgdfg';
////        $this->user_fullName = optional($this->user)->fullName();
////        $this->user_fName = optional($this->user)->fName();
        return [
            'id'=> $this->id,
            'number'=>$this->number,
            'size'=>$this->size,
            'user_fullName'=>optional($this->user)->fullName(),
            'user_fName'=>optional($this->user)->fName(),
            'user'=> $this->user ? $this->user : [],
            'discriptions' => $this->discriptions
        ];

//        return parent::toArray($request);
    }
}
