<?php
namespace App\Modules\User\Repositories;


use App\Models\Laratrust\Permission;
use App\Modules\User\Models\UserModel;

class GetPermissionsForUserRepository{

    private $permissions;

    public function __construct(UserModel $user)
    {
        $this->permissions = $user->permissions();
        if ($user->hasRole('superAdmin')) {
            $this->permissions = Permission::where('name', 'not like', 'ban-%')->get();
        }
    }

    public function run()
    {
        return $this->permissions;
    }

    public function toArray()
    {
//        dd($this->permissions->count());
        if ($this->permissions && $this->permissions->count() > 0) {
            return $this->permissions->map(function ($item) {
                return $item->name;
            });
        }
        return [];
    }




}