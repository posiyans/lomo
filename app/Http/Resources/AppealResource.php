<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppealResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return ['id'=>$this->id];
        $this->user->fullName = $this->user->fullName();;
        $this->user->fName = $this->user->fName();;
        $this->message;
        return parent::toArray($request);
    }
}
