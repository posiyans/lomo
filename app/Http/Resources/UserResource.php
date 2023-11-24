<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $roles = [];
        foreach ($this->roles as $role) {
            $roles[] = $role->name;
        }
        $permissions = [];
        foreach ($this->permissions as $permission) {
            $permissions[] = $permission->name;
        }
        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'last_name' => $this->last_name,
            'name' => $this->name,
            'middle_name' => $this->middle_name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at ? true : false,
            'phone' => $this->phone,
            'adres' => $this->adres,
            'created_at' => $this->created_at,
            'last_connect' => $this->last_connect,
            'roles' => ['roles' => $roles, 'permissions' => $permissions],

        ];
//        return parent::toArray($request);
    }
}
