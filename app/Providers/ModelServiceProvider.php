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
        $this->app->bind('App\Models\Interfaces\StudioInterface', 'App\Models\Studio');
        $this->app->bind('App\Models\Interfaces\StudioTypeInterface', 'App\Models\StudioType');
        $this->app->bind('App\Models\Interfaces\StudioLocationInterface', 'App\Models\StudioLocation');
        $this->app->bind('App\Models\Interfaces\StudioPriceInterface', 'App\Models\StudioPrice');
        $this->app->bind('App\Models\Interfaces\StudioImageInterface', 'App\Models\StudioImage');
        $this->app->bind('App\Models\Interfaces\CustomerFavouriteInterface', 'App\Models\CustomerFavourite');
    }
}
