<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'about', 'photo', 'occupation', 'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return BelongsToMany
     */
    public function socialmedia()
    {
        return $this->belongsToMany('App\Models\Socialmedia', 'user_socialmedia', 'user_id', 'socialmedia_id')
        ->withTimestamps()
        ->withPivot('url');
    }

    /**
     * @return string
     */
    public function adminlte_image()
    {
        if (Auth::user()->photo) {
            if (Auth::user()->photo != 'noavatar.png') {
                return route('profile.photo', Auth::user()->photo);
            } else {
                return asset('img/noavatar.png');
            }
        } else {
            return asset('img/noavatar.png');
        }
    }

    /**
     * @return string
     */
    public function adminlte_desc()
    {
        $roles = [];
        foreach (Auth::user()->getRoleNames() as $role) {
            $roles[] = $role;
        }
        return implode(' ', $roles);
    }

    /**
     * @return string
     */
    public function adminlte_profile_url()
    {
        return 'admin/profile';
    }

    /**
     * @param Builder $query
     * @param $roles
     * @param null $guard
     * @return Builder
     */
    public function scopeNotRole(Builder $query, $roles, $guard = null): Builder
    {
        if ($roles instanceof Collection) {
            $roles = $roles->all();
        }

        if (! is_array($roles)) {
            $roles = [$roles];
        }

        $roles = array_map(function ($role) use ($guard) {
            if ($role instanceof Role) {
                return $role;
            }

            $method = is_numeric($role) ? 'findById' : 'findByName';
            $guard = $guard ?: $this->getDefaultGuardName();

            return $this->getRoleClass()->{$method}($role, $guard);
        }, $roles);

        return $query->whereHas('roles', function ($query) use ($roles) {
            $query->where(function ($query) use ($roles) {
                foreach ($roles as $role) {
                    $query->where(config('permission.table_names.roles').'.id', '!=' , $role->id);
                }
            });
        });
    }

    /**
     * @param $keyword
     * @return mixed
     */
    public static function searchRole($keyword) {
        $roles = Role::all()->reject(function ($role) {
            return $role->name === 'superadmin';
        })->map(function ($role) {
            return 'read-' . $role->name;
        })->toArray();

        $user  = Auth::user();
        $perms = $user->getAllPermissions()->whereIn('alias', $roles)->flatten()->toArray();

        $roleName = [];
        foreach ($perms as $perm) {
            $name    = $perm['alias'];
            $roleName[] = last(explode('-', $name));
        }

        return Role::select('id','name')
            ->whereIn('name', $roleName)
            ->where("name", "LIKE", "%$keyword%")->get();
    }

    /**
     * @param $id
     */
    public static function checkUserAuthorization($id) {
        if (Auth::User()->hasRole('master')) {
            if(User::findOrFail($id)->hasRole('master')
                && Auth::id() != $id){
                abort('403');
            }
        } else if (Auth::User()->hasRole('superadmin')) {
            if (User::findOrFail($id)->hasRole('master')) {
                abort('403');
            } else {
                if (User::findOrFail($id)->hasRole('superadmin')
                    && Auth::id() != $id) {
                    abort('403');
                }
            }
        } else if(Auth::User()->hasRole(['admin'])) {
            if (User::findOrFail($id)->hasRole('master', 'superadmin')) {
                abort('403');
            } else {
                if (User::findOrFail($id)->hasRole('admin')
                    && Auth::id() != $id) {
                    abort('403');
                }
            }
        } else {
            if (User::findOrFail($id)->hasRole(['master', 'superadmin','admin'])) {
                abort('403');
            } else {
                if (User::findOrFail($id)->hasRole('member')
                    && Auth::id() != $id) {
                    abort('403');
                }
            }
        }
    }

    /**
     * @return array
     */
    public static function showRoles()
    {
        $roles = Role::all()->reject(function ($role) {
            return $role->name === 'superadmin';
        })->map(function ($role) {
            return 'read-' . $role->name;
        })->toArray();

        $user  = Auth::user();
        $perms = $user->getAllPermissions()->whereIn('name', $roles)->flatten()->toArray();

        $roles = [];
        foreach ($perms as $perm) {
            $name    = $perm['name'];
            $roles[] = last(explode('-', $name));
        }
        return $roles;
    }
}
