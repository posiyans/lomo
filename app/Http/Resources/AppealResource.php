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
        if ($this->user) {
            $user = ['fullName' => $this->user->fullName(),'fName' => $this->user->fName()];
        } else {
            $user = ['fullName' => '-','fName' => '-'];
        }
        return [
            'id' => $this->id,
            'user' => $user,
            'message' => $this->message,
            'title' => $this->title,
            'text' => $this->text,
            'created_at' => $this->created_at,
            'type' => $this->type,
            'status' => $this->status,

        ];
    }
}
