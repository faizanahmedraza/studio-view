<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 *
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    /**
     * Bind the interface to an implementation repository class
     */
    public function register()
    {
        $this->app->bind('App\Repositories\Interfaces\AdminRepositoryInterface', 'App\Repositories\AdminRepository');
        $this->app->bind('App\Repositories\Interfaces\SiteSettingRepositoryInterface', 'App\Repositories\SiteSettingRepository');
        $this->app->bind('App\Repositories\Interfaces\UserRepositoryInterface', 'App\Repositories\UserRepository');
        $this->app->bind('App\Repositories\Interfaces\RoleRepositoryInterface','App\Repositories\RolesRepository');
        $this->app->bind('App\Repositories\Interfaces\PagesRepositoryInterface', 'App\Repositories\PagesRepository');
    }
}
