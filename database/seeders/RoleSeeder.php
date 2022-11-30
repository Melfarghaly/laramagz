<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // analytics
        $read_analytics   = Permission::create(['id' => 1, 'name' => 'read-analytics', 'alias' => 'read-analytics']);
        $add_analytics    = Permission::create(['id' => 2, 'name' => 'add-analytics', 'alias' => 'add-analytics']);
        $update_analytics = Permission::create(['id' => 3, 'name' => 'update-analytics', 'alias' => 'update-analytics']);
        $delete_analytics = Permission::create(['id' => 4, 'name' => 'delete-analytics', 'alias' => 'delete-analytics']);

        // posts
        $read_posts   = Permission::create(['id' => 5, 'name' => 'read-posts', 'alias' => 'read-posts']);
        $add_posts    = Permission::create(['id' => 6, 'name' => 'add-posts', 'alias' => 'add-posts']);
        $update_posts = Permission::create(['id' => 7, 'name' => 'update-posts', 'alias' => 'update-posts']);
        $delete_posts = Permission::create(['id' => 8, 'name' => 'delete-posts', 'alias' => 'delete-posts']);

        // categories
        $read_categories   = Permission::create(['id' => 9, 'name' => 'read-categories', 'alias' => 'read-categories']);
        $add_categories    = Permission::create(['id' => 10, 'name' => 'add-categories', 'alias' => 'add-categories']);
        $update_categories = Permission::create(['id' => 11, 'name' => 'update-categories', 'alias' => 'update-categories']);
        $delete_categories = Permission::create(['id' => 12, 'name' => 'delete-categories', 'alias' => 'delete-categories']);

        // tags
        $read_tags   = Permission::create(['id' => 13, 'name' => 'read-tags', 'alias' => 'read-tags']);
        $add_tags    = Permission::create(['id' => 14, 'name' => 'add-tags', 'alias' => 'add-tags']);
        $update_tags = Permission::create(['id' => 15, 'name' => 'update-tags', 'alias' => 'update-tags']);
        $delete_tags = Permission::create(['id' => 16, 'name' => 'delete-tags', 'alias' => 'delete-tags']);

        // pages
        $read_pages   = Permission::create(['id' => 17, 'name' => 'read-pages', 'alias' => 'read-pages']);
        $add_pages    = Permission::create(['id' => 18, 'name' => 'add-pages', 'alias' => 'add-pages']);
        $update_pages = Permission::create(['id' => 19, 'name' => 'update-pages', 'alias' => 'update-pages']);
        $delete_pages = Permission::create(['id' => 20, 'name' => 'delete-pages', 'alias' => 'delete-pages']);

        // contacts
        $read_contacts   = Permission::create(['id' => 21, 'name' => 'read-contacts', 'alias' => 'read-contacts']);
        $add_contacts    = Permission::create(['id' => 22, 'name' => 'add-contacts', 'alias' => 'add-contacts']);
        $update_contacts = Permission::create(['id' => 23, 'name' => 'update-contacts', 'alias' => 'update-contacts']);
        $delete_contacts = Permission::create(['id' => 24, 'name' => 'delete-contacts', 'alias' => 'delete-contacts']);

        // menus
        $read_menus   = Permission::create(['id' => 25, 'name' => 'read-menus', 'alias' => 'read-menus']);
        $add_menus    = Permission::create(['id' => 26, 'name' => 'add-menus', 'alias' => 'add-menus']);
        $update_menus = Permission::create(['id' => 27, 'name' => 'update-menus', 'alias' => 'update-menus']);
        $delete_menus = Permission::create(['id' => 28, 'name' => 'delete-menus', 'alias' => 'delete-menus']);

        // galleries
        $read_galleries   = Permission::create(['id' => 29, 'name' => 'read-galleries', 'alias' => 'read-galleries']);
        $add_galleries    = Permission::create(['id' => 30, 'name' => 'add-galleries', 'alias' => 'add-galleries']);
        $update_galleries = Permission::create(['id' => 31, 'name' => 'update-galleries', 'alias' => 'update-galleries']);
        $delete_galleries = Permission::create(['id' => 32, 'name' => 'delete-galleries', 'alias' => 'delete-galleries']);

        // filemanager
        $read_filemanager   = Permission::create(['id' => 33, 'name' => 'read-filemanager', 'alias' => 'read-filemanager']);
        $add_filemanager    = Permission::create(['id' => 34, 'name' => 'add-filemanager', 'alias' => 'add-filemanager']);
        $update_filemanager = Permission::create(['id' => 35, 'name' => 'update-filemanager', 'alias' => 'update-filemanager']);
        $delete_filemanager = Permission::create(['id' => 36, 'name' => 'delete-filemanager', 'alias' => 'delete-filemanager']);

        // ads
        $read_ads   = Permission::create(['id' => 37, 'name' => 'read-ads', 'alias' => 'read-ads']);
        $add_ads    = Permission::create(['id' => 38, 'name' => 'add-ads', 'alias' => 'add-ads']);
        $update_ads = Permission::create(['id' => 39, 'name' => 'update-ads', 'alias' => 'update-ads']);
        $delete_ads = Permission::create(['id' => 40, 'name' => 'delete-ads', 'alias' => 'delete-ads']);

        // video youtube
        $read_yt   = Permission::create(['id' => 41, 'name' => 'read-video-youtube', 'alias' => 'read-video-youtube']);
        $add_yt    = Permission::create(['id' => 42, 'name' => 'add-video-youtube', 'alias' => 'add-video-youtube']);
        $update_yt = Permission::create(['id' => 43, 'name' => 'update-video-youtube', 'alias' => 'update-video-youtube']);
        $delete_yt = Permission::create(['id' => 44, 'name' => 'delete-video-youtube', 'alias' => 'delete-video-youtube']);

        // users
        $read_users   = Permission::create(['id' => 45, 'name' => 'read-users', 'alias' => 'read-users']);
        $add_users    = Permission::create(['id' => 46, 'name' => 'add-users', 'alias' => 'add-users']);
        $update_users = Permission::create(['id' => 47, 'name' => 'update-users', 'alias' => 'update-users']);
        $delete_users = Permission::create(['id' => 48, 'name' => 'delete-users', 'alias' => 'delete-users']);

        // roles
        $read_roles   = Permission::create(['id' => 49, 'name' => 'read-roles', 'alias' => 'read-roles']);
        $add_roles    = Permission::create(['id' => 50, 'name' => 'add-roles', 'alias' => 'add-roles']);
        $update_roles = Permission::create(['id' => 51, 'name' => 'update-roles', 'alias' => 'update-roles']);
        $delete_roles = Permission::create(['id' => 52, 'name' => 'delete-roles', 'alias' => 'delete-roles']);

        // permissions
        $read_permissions   = Permission::create(['id' => 53, 'name' => 'read-permissions', 'alias' => 'read-permissions']);
        $add_permissions    = Permission::create(['id' => 54, 'name' => 'add-permissions', 'alias' => 'add-permissions']);
        $update_permissions = Permission::create(['id' => 55, 'name' => 'update-permissions', 'alias' => 'update-permissions']);
        $delete_permissions = Permission::create(['id' => 56, 'name' => 'delete-permissions', 'alias' => 'delete-permissions']);

        // social-media
        $read_social_media   = Permission::create(['id' => 57, 'name' => 'read-social-media', 'alias' => 'read-social-media']);
        $add_social_media    = Permission::create(['id' => 58, 'name' => 'add-social-media', 'alias' => 'add-social-media']);
        $update_social_media = Permission::create(['id' => 59, 'name' => 'update-social-media', 'alias' => 'update-social-media']);
        $delete_social_media = Permission::create(['id' => 60, 'name' => 'delete-social-media', 'alias' => 'delete-social-media']);

        // settings
        $read_settings   = Permission::create(['id' => 61, 'name' => 'read-settings', 'alias' => 'read-settings']);
        $add_settings    = Permission::create(['id' => 62, 'name' => 'add-settings', 'alias' => 'add-settings']);
        $update_settings = Permission::create(['id' => 63, 'name' => 'update-settings', 'alias' => 'update-settings']);
        $delete_settings = Permission::create(['id' => 64, 'name' => 'delete-settings', 'alias' => 'delete-settings']);

        // env
        $read_env   = Permission::create(['id' => 65, 'name' => 'read-env', 'alias' => 'read-env']);
        $add_env    = Permission::create(['id' => 66, 'name' => 'add-env', 'alias' => 'add-env']);
        $update_env = Permission::create(['id' => 67, 'name' => 'update-env', 'alias' => 'update-env']);
        $delete_env = Permission::create(['id' => 68, 'name' => 'delete-env', 'alias' => 'delete-env']);

        // themes
        $read_themes   = Permission::create(['id' => 69, 'name' => 'read-themes', 'alias' => 'read-themes']);
        $add_themes    = Permission::create(['id' => 70, 'name' => 'add-themes', 'alias' => 'add-themes']);
        $update_themes = Permission::create(['id' => 71, 'name' => 'update-themes', 'alias' => 'update-themes']);
        $delete_themes = Permission::create(['id' => 72, 'name' => 'delete-themes', 'alias' => 'delete-themes']);

        // profile
        $read_profile   = Permission::create(['id' => 73, 'name' => 'read-profile', 'alias' => 'read-profile']);
        $add_profile    = Permission::create(['id' => 74, 'name' => 'add-profile', 'alias' => 'add-profile']);
        $update_profile = Permission::create(['id' => 75, 'name' => 'update-profile', 'alias' => 'update-profile']);
        $delete_profile = Permission::create(['id' => 76, 'name' => 'delete-profile', 'alias' => 'delete-profile']);

        // admin
        $read_admin        = Permission::create(['id' => 77, 'name' => 'read-admin', 'alias' => 'read-admin']);
        $add_admin         = Permission::create(['id' => 78, 'name' => 'add-admin', 'alias' => 'add-admin']);
        $update_admin      = Permission::create(['id' => 79, 'name' => 'update-admin', 'alias' => 'update-admin']);
        $delete_admin      = Permission::create(['id' => 80, 'name' => 'delete-admin', 'alias' => 'delete-admin']);
        $edit_post_admin   = Permission::create(['id' => 81, 'name' => 'edit-post-admin', 'alias' => 'edit-post-admin']);
        $delete_post_admin = Permission::create(['id' => 82, 'name' => 'delete-post-admin', 'alias' => 'delete-post-admin']);

        $read_member        = Permission::create(['id' => 83, 'name' => 'read-member', 'alias' => 'read-member']);
        $add_member         = Permission::create(['id' => 84, 'name' => 'add-member', 'alias' => 'add-member']);
        $update_member      = Permission::create(['id' => 85, 'name' => 'update-member', 'alias' => 'update-member']);
        $delete_member      = Permission::create(['id' => 86, 'name' => 'delete-member', 'alias' => 'delete-member']);
        $edit_post_member   = Permission::create(['id' => 87, 'name' => 'edit-post-member', 'alias' => 'edit-post-member']);
        $delete_post_member = Permission::create(['id' => 88, 'name' => 'delete-post-member', 'alias' => 'delete-post-member']);

        // register
        $register = Permission::create(['id' => 89, 'name' => 'register-member', 'alias' => 'register-member']);

        // private post
        $read_private_post   = Permission::create(['id' => 90, 'name' => 'read-private-post', 'alias' => 'read-private-post']);
        $add_private_post    = Permission::create(['id' => 91, 'name' => 'add-private-post', 'alias' => 'add-private-post']);
        $update_private_post = Permission::create(['id' => 92, 'name' => 'update-private-post', 'alias' => 'update-private-post']);
        $delete_private_post = Permission::create(['id' => 93, 'name' => 'delete-private-post', 'alias' => 'delete-private-post']);

        // Role
        Role::create(['name' => 'superadmin'])->givePermissionTo(Permission::all());
        Role::create(['name' => 'admin'])->givePermissionTo(Permission::all()->except([65, 66, 67, 68, 77, 78, 79, 80]));
        Role::create(['name' => 'member'])->givePermissionTo([
            $read_posts, $add_posts, $update_posts, $delete_posts,
            $update_users,
            $read_profile, $add_profile, $update_profile, $delete_profile]);
    }
}
