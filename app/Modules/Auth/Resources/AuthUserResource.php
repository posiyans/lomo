<?php

namespace App\Modules\Auth\Resources;

use App\Modules\User\Repositories\GetPermissionsForUserRepository;
use App\Modules\User\Resources\UserInfoResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = parent::toArray($request);
        $roles = $this->roles->map(function ($role) {
            return $role->name;
        });
        $permissions = array_merge(['user'], (new GetPermissionsForUserRepository($this->resource))->toArray(), $roles->toArray());
        $result = [
            'user' => new UserInfoResource($this->resource),
            'permissions' => $permissions,
            'roles' => $roles
        ];
        return $result;
    }
}
