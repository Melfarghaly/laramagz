<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#61-title
    |
    */

    'title' => 'Laramagz',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#62-favicon
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => true,
    'use_custom_favicon' => false,
    'path_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#63-logo
    |
    */

    'logo' => '<b>Laramagz</b>',
    'logo_img' => 'img/logo.png',
    'logo_img_auth' => 'img/logo-auth.png',
    'logo_img_class' => 'brand-image elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Laramagz',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#64-user-menu
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => true,
    'usermenu_desc' => true,
    'usermenu_profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#71-layout
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#721-authentication-views-classes
    |
    */

    'classes_auth_card' => '',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#722-admin-panel-classes
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => 'container-fluid',
    'classes_content' => 'container-fluid',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => 'nav-legacy',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#73-sidebar
    |
    */

    'sidebar_mini' => true,
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#74-control-sidebar-right-sidebar
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#65-urls
    |
    */

    'use_route_url' => false,

    'dashboard_url' => 'admin',

    'logout_url' => 'logout',

    'login_url' => 'login',

    'register_url' => 'register',

    'password_reset_url' => 'password/reset',

    'password_email_url' => 'password/email',

    'profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#92-laravel-mix
    |
    */

    'enabled_laravel_mix' => true,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#8-menu-configuration
    |
    */

    'menu' => [
        [
            'text'   => 'Visit Site',
            'url'    => '/',
            'icon'   => 'fas fa-desktop',
            'target' => '_blank',
            'topnav' => true,
        ],
        [
            'text' => 'dashboard',
            'url'  => 'admin/dashboard',
            'icon' => 'fas fa-tachometer-alt',
        ],
        ['header'   => 'manage_content', 'can'  => ['read-posts','read-pages']],
        [
            'text'          => 'Posts',
            'icon'          => 'fas fa-book',
            'can'           => 'read-posts',
            'submenu'       => [
                [
                    'text'      => 'All Posts',
                    'url'       => 'admin/manage/posts',
                    'can'       => 'read-posts',
                    'active'    => ['admin/manage/posts', 'admin/manage/posts/*/edit'],
                ],
                [
                    'text'      => 'Add New Post',
                    'can'       => 'add-posts',
                    'url'       => 'admin/manage/posts/create',
                ],
                [
                    'text'      => 'Categories',
                    'url'       => 'admin/manage/categories',
                    'can'       => 'read-categories',
                    'active'    => ['admin/manage/categories', 'admin/manage/categories/*/edit'],
                ],
                [
                    'text'      => 'Tags',
                    'url'       => 'admin/manage/tags',
                    'can'       => 'read-tags',
                    'active'    => ['admin/manage/tags', 'admin/manage/tags/*/edit'],
                ]
            ]
        ],
        [
            'text'              => 'Pages',
            'icon'              => 'fas fa-copy',
            'can'               => 'read-pages',
            'submenu'           => [
                [
                    'text'          => 'All Pages',
                    'url'           => 'admin/manage/pages',
                    'can'           => 'read-pages',
                    'active'        => ['admin/manage/pages', 'admin/manage/pages/*/edit'],
                ],
                [
                    'text'          => 'Add New',
                    'url'           => 'admin/manage/pages/create',
                    'can'           => 'add-pages',
                    'active'        => ['admin/manage/pages/create']
                ]
            ]
        ],
        [
            'text'        => 'Contacts',
            'url'         => 'admin/manage/contacts',
            'can'         => 'read-contacts',
            'icon'        => 'fa fa-envelope'
        ],
        [
            'text'          => 'Appearance',
            'icon'          => 'fas fa-brush',
            'can'           => 'read-menus',
            'submenu'       => [
                [
                    'text'      => 'Menu',
                    'url'       => 'admin/manage/menu?menu=1',
                    'can'       => 'read-menus',
                    'active'    => ['admin/manage/menu']
                ],
                [
                    'text'      => 'Themes',
                    'url'       => 'admin/manage/themes',
                ],
            ]
        ],
        [
            'text'    => 'Advertisement',
            'icon'    => 'fas fa-bullhorn',
            'can'     => 'read-ads',
            'active'  => ['admin/manage/advertisement', 'admin/manage/advertisement/*/edit'],
            'submenu' => [
                [
                    'text'      => 'Placements',
                    'url'       => 'admin/manage/placements',
                    'active'    => ['admin/manage/placements', 'admin/manage/placements/*/edit']
                ],
                [
                    'text'      => 'Ad Unit',
                    'url'       => 'admin/manage/advertisement',
                    'active'      => ['admin/manage/advertisement', 'admin/manage/advertisement/create', 'admin/manage/advertisement/*/edit'],
                ],
            ]
        ],
        ['header' => 'manage_files', 'can'  => 'read-galleries',],
        [
            'text'        => 'Media',
            'icon'        => 'fas fa-hdd',
            'can'         => 'read-galleries',
            'submenu'     => [
                [
                    'text'      => 'Gallery',
                    'url'       => 'admin/manage/galleries',
                    'can'       => 'read-galleries',
                    'active'    => ['admin/manage/galleries', 'admin/manage/galleries/*/edit'],
                ],
                [
                    'text'      => 'Filemanager',
                    'can'       => 'read-filemanager',
                    'url'       => 'admin/manage/filemanager',
                ],
            ]
        ],
        ['header' => 'account_settings'],
        [
            'text' => 'profile',
            'url'  => 'admin/profile',
            'active' => ['admin/profile/*'],
            'icon' => 'fas fa-fw fa-user',
        ],
        [
            'text' => 'change_password',
            'url'  => 'admin/change-password',
            'icon' => 'fas fa-fw fa-lock',
        ],
        ['header' => 'manage_users', 'can'  => 'read-users'],
        [
            'text'        => 'Users',
            'icon'        => 'fas fa-users',
            'can'         => 'read-users',
            'submenu'     => [
                [
                    'text'      => 'All Users',
                    'url'       => 'admin/manage/users',
                    'can'       => 'read-users',
                    'active'    => ['admin/manage/users', 'admin/manage/users/*/edit'],
                ],
                [
                    'text'      => 'Add New Users',
                    'can'       => 'add-users',
                    'url'       => 'admin/manage/users/create',
                ],
                [
                    'text'      => 'Roles',
                    'url'       => 'admin/manage/roles',
                    'can'       => 'read-roles',
                    'active'    => ['admin/manage/roles', 'admin/manage/roles/*', 'admin/manage/roles/*/edit']
                ],
                [
                    'text'      => 'Permission',
                    'url'       => 'admin/manage/permissions',
                    'can'       => 'read-permissions',
                    'active'    => ['admin/manage/permissions', 'admin/manage/permissions/*', 'admin/manage/permissions/*/edit']
                ]

            ]
        ],
        [
            'text'        => 'Social Media',
            'url'         => 'admin/manage/socialmedia',
            'can'         => 'read-social-media',
            'icon'        => 'fa fa-globe',
        ],
        ['header' => 'manage_settings', 'can'  => 'read-settings'],
        [
            'text'        => 'Settings',
            'url'         => 'admin/manage/settings',
            'can'         => 'read-settings',
            'icon'        => 'fas fa-cogs',
        ],
        [
            'text'        => 'Env Editor',
            'url'         => 'admin/manage/env',
            'can'         => 'read-env',
            'icon'        => 'far fa-file',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#83-custom-menu-filters
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#91-plugins
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#93-livewire
    */

    'livewire' => false,
];
