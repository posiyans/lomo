<?php

namespace Database\Seeders\Demo;

use App\Models\Laratrust\Role;
use App\Modules\User\Models\UserModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserModelDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo get_class($this) . "\n";
        $user = UserModel::create([
            'name' => 'admin',
            'email' => 'admin@examples.com',
            'email_verified_at' => '2020-10-10 10:10:10',
            'password' => Hash::make('password'),
            'uid' => Str::uuid(),
            'options' => [
                'adres' => '',
                'phone' => '',
            ],
        ]);
        $role = Role::where('name', 'superAdmin')->first();
        if ($role) {
            $user->syncRolesWithoutDetaching([$role->id]);
        }
        UserModel::factory()->count(50)->create();
    }
}
