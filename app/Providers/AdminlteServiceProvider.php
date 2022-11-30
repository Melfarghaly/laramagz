<?php

namespace App\Providers;

use App\Helpers\Settings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AdminlteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('settings')) {
            if (Settings::get('favicon')) {
                config(['adminlte.use_full_favicon' => false]);
                config(['adminlte.use_custom_favicon' => true]);
                config(['adminlte.path_favicon' => url('icon/' . Settings::get('favicon'))
                ]);
            }

            config(['adminlte.logo' => '<b>' . Settings::get('company_name') . '</b>']);

            if (Settings::get('logodashboard')) {
                config(['adminlte.logo_img' => url('dashboard/logo/'.Settings::get('logodashboard'))]);
            } else {
                config(['adminlte.logo_img' => 'img/logo.png']);
            }

            if (Settings::get('logoauth')) {
                config(['adminlte.logo_img_auth' => url('auth/logo/'.Settings::get('logoauth'))]);
            } else {
                config(['adminlte.logo_img_auth' => 'img/logo-auth.png']);
            }
        }
    }
}
