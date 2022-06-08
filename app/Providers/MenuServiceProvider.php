<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class MenuServiceProvider
 *
 * @author
 */
class MenuServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->make('App\Services\Menu\AdminMenu')->register();
    }

    public function register()
    {
        //
    }
}
