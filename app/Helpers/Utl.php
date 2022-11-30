<?php

namespace App\Helpers;

use App\Models\AdPlacement;
use App\Models\Advertisement;
use App\Models\Post;
use App\Models\Socialmedia;
use App\Models\Term;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Utl
{
    /**
     * @return mixed
     */
    public static function postCount()
    {
        return Post::wherePostType('post')->count();
    }

    /**
     * @return mixed
     */
    public static function categoryCount()
    {
        $getDataTaxonomy = Term::with('taxonomy')->get();
        return $getDataTaxonomy->first()->taxonomy->where('taxonomy', 'category')->count();
    }

    /**
     * @return mixed
     */
    public static function tagCount()
    {
        $getDataTaxonomy = Term::with('taxonomy')->get();
        return $getDataTaxonomy->first()->taxonomy->where('taxonomy', 'tag')->count();
    }

    /**
     * @return mixed
     */
    public static function userCount()
    {
        if (Auth::user()->hasRole('superadmin')) {
            return User::count();
        } else {
            $roles = User::showRoles();
            return User::role($roles)->count();
        }
    }

    /**
     * @return mixed
     */
    public static function roleCount()
    {
        $roles = User::showRoles();
        return Role::whereIn('name', $roles)->count();
    }

    /**
     * @return mixed
     */
    public static function permissioncount() {
        return Permission::count();
    }

    /**
     * @return mixed
     */
    public static function socialmediaCount() {
        return Socialmedia::count();
    }

    /**
     * @return mixed
     */
    public static function galleryCount() {
        return Post::ofType('gallery')->count();
    }

    /**
     * @return mixed
     */
    public static function advertisementcount() {
        return Advertisement::count();
    }

    /**
     * @return mixed
     */
    public static function adplacementcount() {
        return AdPlacement::count();
    }
}
