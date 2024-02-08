<?php

namespace App\Modules\User\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'uid' => $this->uid,
            'last_name' => $this->last_name,
            'name' => $this->name,
            'middle_name' => $this->middle_name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at ? true : false,
            'created_at' => $this->created_at,
            'consent_to_email' => $this->consent_to_email,
            'last_connect' => $this->last_connect,
            'owner' => $this->owner ? ['uid' => $this->owner->uid] : null,
        ];
        $options = $this->options;
        return array_merge($data, $options);
    }
}
