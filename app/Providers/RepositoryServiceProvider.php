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
        $this->app->bind('App\Repositories\Interfaces\StudioRepositoryInterface', 'App\Repositories\StudioRepository');
        $this->app->bind('App\Repositories\Interfaces\StudioTypeRepositoryInterface', 'App\Repositories\StudioTypeRepository');
        $this->app->bind('App\Repositories\Interfaces\TypeRepositoryInterface', 'App\Repositories\TypeRepository');
        $this->app->bind('App\Repositories\Interfaces\StudioLocationRepositoryInterface', 'App\Repositories\StudioLocationRepository');
        $this->app->bind('App\Repositories\Interfaces\StudioPriceRepositoryInterface', 'App\Repositories\StudioPriceRepository');
        $this->app->bind('App\Repositories\Interfaces\StudioImageRepositoryInterface', 'App\Repositories\StudioImageRepository');
        $this->app->bind('App\Repositories\Interfaces\CustomerFavouriteRepositoryInterface', 'App\Repositories\CustomerFavouriteRepository');
        $this->app->bind('App\Repositories\Interfaces\StudioBookingRepositoryInterface', 'App\Repositories\StudioBookingRepository');
        $this->app->bind('App\Repositories\Interfaces\InvoiceRepositoryInterface', 'App\Repositories\InvoiceRepository');

    }
}
