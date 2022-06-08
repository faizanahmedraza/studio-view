<?php

/**
 * routes/breadcrumbs.php
 *
 * @author
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
    $breadcrumbs->push('Customers List', route('users.index'));
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
| Studio
|--------------------------------------------------------------------------
*/
Breadcrumbs::for ('studio.index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard.index');
    $breadcrumbs->push('Studio List', route('studio.index'));
});
Breadcrumbs::for ('studio.pending.index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard.index');
    $breadcrumbs->push('Studio Pending List', route('studio.pending.index'));
});
Breadcrumbs::for ('studio.create', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard.index');
    $breadcrumbs->push('Create', route('studio.create'));
});
Breadcrumbs::for ('studio.edit', function ($breadcrumbs,$data) {
    $breadcrumbs->parent('studio.index',$data);
    $breadcrumbs->push('Edit', route('studio.edit',$data->id));
});
Breadcrumbs::for ('studio.booking_details', function ($breadcrumbs,$data) {
    $breadcrumbs->parent('invoice.index',$data);
    $breadcrumbs->push('Invoice List', route('studio-booking.detail',$data->id));
});

/*
|--------------------------------------------------------------------------
| Studio Types
|--------------------------------------------------------------------------
*/
Breadcrumbs::for ('studio.type.index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard.index');
    $breadcrumbs->push('Studio Type List', route('studio.type.index'));
});
Breadcrumbs::for ('studio.type.create', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard.index');
    $breadcrumbs->push('Create', route('studio.type.create'));
});
Breadcrumbs::for ('studio.type.edit', function ($breadcrumbs,$data) {
    $breadcrumbs->parent('studio.type.index',$data);
    $breadcrumbs->push('Edit', route('studio.type.edit',$data->id));
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

/*
|--------------------------------------------------------------------------
| Invoices
|--------------------------------------------------------------------------
*/
Breadcrumbs::for ('invoice.index', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard.index');
    $breadcrumbs->push('Invoice List', route('invoice.index'));
});
// Breadcrumbs::for ('studio.type.create', function ($breadcrumbs) {
//     $breadcrumbs->parent('dashboard.index');
//     $breadcrumbs->push('Create', route('studio.type.create'));
// });
// Breadcrumbs::for ('studio.type.edit', function ($breadcrumbs,$data) {
//     $breadcrumbs->parent('studio.type.index',$data);
//     $breadcrumbs->push('Edit', route('studio.type.edit',$data->id));
// });

