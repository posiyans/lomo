<?php

namespace App\Models\Laratrust;

use Laratrust\Models\LaratrustPermission;

/**
 * App\Models\Laratrust\Permission
 *
 * @property int $id
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Role> $roles
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Role> $roles
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Role> $roles
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Laratrust\Role> $roles
 * @mixin \Eloquent
 */
class Permission extends LaratrustPermission
{
    //
}
