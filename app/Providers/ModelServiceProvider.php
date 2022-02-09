<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ModelServiceProvider
 *
 */
class ModelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    /**
     * Bind the interface to an implementation model class
     */
    public function register()
    {
        $this->app->bind('App\Models\Interfaces\UserInterface', 'App\Models\User');
        $this->app->bind('App\Models\Interfaces\SiteSettingInterface', 'App\Models\SiteSetting');
        $this->app->bind('App\Models\Interfaces\RoleInterface', 'App\Models\Roles');
        $this->app->bind('App\Models\Interfaces\PermissionsInterface', 'App\Models\Permissions');
        $this->app->bind('App\Models\Interfaces\PagesInterface', 'App\Models\Pages');
    }
}
