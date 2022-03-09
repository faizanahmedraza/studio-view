<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

/**
 * Class ViewComposerServiceProvider
 *
 */
class ViewComposerServiceProvider extends ServiceProvider
{


    public function boot()
    {
        $this->composeAdminPages();
        view()->composer('*', function ($view) {
            $date = date('Y-m-d');

            /*setting default variables */
            $userPermissoins = [];
            $dashbord_cards_rights = true;

            /*checking user is login or not */
            if (Auth::check()) {
                $auth_user = Auth::user();
                // $userPermissoins = $auth_user->getPermissions();
                // $dashbord_cards_rights = $auth_user->DashboardCardRightsArray();
                $userPermissoins = [];
                $dashbord_cards_rights = true;
            }

            /*composing data to all views */
            $view->with(compact(
                'userPermissoins',
                'dashbord_cards_rights'
            ));

        });
    }

    public function register()
    {
        //
    }

    /**
     * Compose the admin pages
     *
     * e-g: admin page titles etc.
     */
    private function composeAdminPages()
    {
        /**
         * Dashboard
         */
        view()->composer('admin.dashboard.index', function ($view) {
            $view->with(['pageTitle' => 'Dashboard']);
        });



        /**
         *Users
         */
        view()->composer('admin.Customer.index', function ($view) {
            $view->with(['pageTitle' => 'Users List']);
        });
        view()->composer('admin.Customer.edit', function ($view) {
            $view->with(['pageTitle' => 'User Edit']);
        });

        view()->composer('admin.Customer.edit-unverified-users', function ($view) {
            $view->with(['pageTitle' => 'User Edit']);
        });

        /**
         * New Request User
         */
        view()->composer('admin.new-request.index', function ($view) {
            $view->with(['pageTitle' => 'New Request List']);
        });

        /**
         * Studio
         */
        view()->composer('admin.studio.index', function ($view) {
            $view->with(['pageTitle' => 'Studio List']);
        });
        view()->composer('admin.studio.pending', function ($view) {
            $view->with(['pageTitle' => 'Studio Pending List']);
        });


        /**
         * Page
         */
        view()->composer('admin.pages.edit', function ($view) {
            $view->with(['pageTitle' => 'Page Edit']);
        });



        /**
         * Change Password
         */
        view()->composer('admin.users.changePassword', function ($view) {
            $view->with(['pageTitle' => 'Change Password']);
        });

        /**
         * Edit Profile
         */
        view()->composer('admin.users.profile', function ($view) {
            $view->with(['pageTitle' => 'Edit Profile']);
        });



    }
}
