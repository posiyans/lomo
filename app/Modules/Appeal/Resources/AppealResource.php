<?php

namespace App\Modules\Appeal\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppealResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = [
            'fullName' => $this->user->fullName(),
            'uid' => $this->user->uid,
            'owner' =>
                [
                    'uid' => $this->user->owner ? $this->user->owner->uid : false
                ],
        ];
        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'user' => $user,
            'title' => $this->title,
            'created_at' => $this->created_at,
            'type' =>
                [
                    'id' => $this->type->id,
                    'label' => $this->type->label,
                    'description' => $this->type->description,
                ],
            'status' => $this->status,
            'close' => [
                'date' => $this->date_close,
                'user' => $this->close_user ? $this->close_user->fullName() : ''
            ]
        ];
    }
}
