<?php

namespace App\Modules\User\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserAllInfoResource extends JsonResource
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
        if ($this->owner) {
            $owner = [
                'uid' => $this->owner->uid,
                'steads' => $this->owner->steads->sortBy('stead.number', SORT_NATURAL)->map(function ($item) {
                    return [
                        'stead_id' => $item->id,
                        'number' => $item->number,
                        'proportion' => $item->pivot->proportion,
                    ];
                })->values(),
            ];
        } else {
            $owner = false;
        }
        $data = [
            'id' => $this->id,
            'uid' => $this->uid,
            'last_name' => $this->last_name,
            'name' => $this->name,
            'middle_name' => $this->middle_name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at ? true : false,
            'options' => $this->options,
            'created_at' => $this->created_at,
            'owner' => $owner,
            'consent_to_email' => $this->consent_to_email,
            'last_connect' => $this->last_connect,
            'roles' => ['roles' => $roles, 'permissions' => $permissions],
        ];
        $fields = $this->getUserPublicFieldName();
        foreach ($fields as $field) {
            $data[$field] = $this->getField($field, '');
        }
        return $data;
    }
}
