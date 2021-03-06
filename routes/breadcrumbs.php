<?php

/**
 * routes/breadcrumbs.php
 *
 * @author Muzafar Ali Jatoi <muzfr7@gmail.com>
 * @Date: 19/9/18
 */

/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

// Dashboard
Breadcrumbs::for ('dashboard.index', function ($breadcrumbs) {
    $breadcrumbs->push('Dashboard', route('dashboard.index'));
});

/*
|--------------------------------------------------------------------------
| Sub Admin
|--------------------------------------------------------------------------
*/
// Breadcrumbs::for ('sub-admin.index', function ($breadcrumbs) {
//     $breadcrumbs->parent('dashboard.index');
//     $breadcrumbs->push('Sub Admins List', route('sub-admin.index'));
// });

// Breadcrumbs::for ('sub-admin.create', function ($breadcrumbs) {
//     $breadcrumbs->parent('sub-admin.index');
//     $breadcrumbs->push('Add', route('sub-admin.create'));
// });

// Breadcrumbs::for ('sub-admin.show', function ($breadcrumbs, $data) {
//     $breadcrumbs->parent('sub-admin.index');
//     $breadcrumbs->push('Show', route('sub-admin.show', $data->id));
// });

// Breadcrumbs::for ('sub-admin.edit', function ($breadcrumbs, $data) {
//     $breadcrumbs->parent('sub-admin.index', $data);
//     $breadcrumbs->push('Edit', route('sub-admin.edit', $data->id));
// });



/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/
Breadcrumbs::for ('users.index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard.index');
    $breadcrumbs->push('Users List', route('users.index'));
});

Breadcrumbs::for ('users.edit', function ($breadcrumbs, $data) {
    $breadcrumbs->parent('users.index', $data);
    $breadcrumbs->push('Edit', route('users.edit', $data->id));
});


/*
|--------------------------------------------------------------------------
| Change Password
|--------------------------------------------------------------------------
*/
Breadcrumbs::for ('users.change-password', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard.index');
    $breadcrumbs->push('Change Password', route('users.change-password'));
});


/*
|--------------------------------------------------------------------------
| Edit Profile
|--------------------------------------------------------------------------
*/
Breadcrumbs::for ('users.profile', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard.index');
    $breadcrumbs->push('Edit Profile', route('users.edit-profile'));
});

/*
|--------------------------------------------------------------------------
| Front
|--------------------------------------------------------------------------
*/
Breadcrumbs::for ('home', function ($breadcrumbs) {
    $breadcrumbs->push('Home', route('index'));
});

