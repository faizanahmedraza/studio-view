<?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

###Logs View###
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('admin', 'IndexController@index')->name('login'); // for redirection purpose

Route::get('reset/success', function (Request $request) {
    return view('admin.auth.passwords.success');
});

###Swagger View###
Route::get('apidocs', static function (Request $request) {
    return view('documentation.index');
});

Route::get('json-file', static function (Request $request) {

    //Get Json File
    $file = file_get_contents(base_path('webservices.json'));
    $decodeFile = json_decode($file, true);
    //Replace Host With Current Host
    $decodeFile['host'] = request()->getHttpHost();
    //Set Base Path
    $decodeFile['basePath'] = str_replace('json-file', 'api/v1', request()->getRequestUri());
    //Set Scheme According To Request
    $decodeFile['schemes'] = $request->isSecure() ? ['https'] : ['http'];
    return response()->json($decodeFile);

})->name('json-url');

###Redirection###
Route::get('/', 'IndexController@index');

###Login###
Route::get('/login', ['uses' => 'Auth\LoginController@showLoginForm', 'as' => 'login']);
Route::post('/login', ['uses' => 'Auth\LoginController@login', 'as' => 'login']);
Route::any('/logout', ['uses' => 'Auth\LoginController@logout', 'as' => 'logout']);

###Reset Password###
Route::post('/password/email', 'Auth\ForgotPasswordController@send_reset_link_email')->name('password.email');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset_password_update')->name('reset.password.update');
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');

Route::get('/password/reset/{email}/{token}/{role_id}', 'Auth\ResetPasswordController@reset_password_from_show')->name('password.reset');


Route::group(['middleware' => 'backendAuthenticate'], function () {

    ###Dashbaord###
    Route::get('/dashboard', ['uses' => 'DashboardController@index', 'as' => 'dashboard.index']);

    Route::group(['middleware' => ['backendAuthenticate', 'PermissionHandler']], function () {


        ###Admin Profile###
        Route::get('/edit-profile', ['uses' => 'UsersController@editProfile', 'as' => 'users.edit-profile']);
        Route::post('/edit-profile', ['uses' => 'UsersController@updateEditProfile', 'as' => 'users.edit-profile']);

        ###Change Password###
        Route::get('/change-password', ['uses' => 'UsersController@changePassword', 'as' => 'users.change-password']);
        Route::post('/change-password', ['uses' => 'UsersController@processChangePassword', 'as' => 'users.change-password']);

        ###Users###
        Route::get('users', 'CustomerController@Index')->name('users.index');
        Route::get('users/list/data', 'CustomerController@subAdminList')->name('customer.users.data');
        Route::get('customer/edit/{user}', 'CustomerController@edit')->name('users.edit');
        Route::put('customer/update/{user}', 'CustomerController@update')->name('users.update');
        Route::get('users/active/{record}', 'CustomerController@active')->name('users.active');
        Route::get('users/block/{record}', 'CustomerController@block')->name('users.block');


        ###studio routes###
        Route::get('studios', 'StudioController@index')->name('studio.index');
        Route::get('studios/list/data', 'StudioController@studiosList')->name('studios.list.data');
        Route::get('studios-pending', 'StudioController@indexPending')->name('studio.pending.index');
        Route::get('studios/list-pending/data', 'StudioController@studiosListPending')->name('studios.pending.list.data');
        Route::get('studio/toggle-status/{studio}', 'StudioController@toggleStatus')->name('studio.toggle.status');

        //studio crud
        Route::get('studios/create', 'StudioController@create')->name('studio.create');
        Route::post('studios/create', 'StudioController@store')->name('studio.store');
        Route::get('studios/edit/{studio}', 'StudioController@edit')->name('studio.edit');
        Route::put('studios/edit/{studio}', 'StudioController@update')->name('studio.update');

        Route::get('studios/types', 'StudioTypeController@index')->name('studio.type.index');
        Route::get('studios/types/list/data', 'StudioTypeController@studiosList')->name('studio.type.list.data');
        Route::get('studios/types/create', 'StudioTypeController@create')->name('studio.type.create');
        Route::post('studios/types/create', 'StudioTypeController@store')->name('studio.type.store');
        Route::get('studios/types/edit/{type}', 'StudioTypeController@edit')->name('studio.type.edit');
        Route::put('studios/types/edit/{type}', 'StudioTypeController@update')->name('studio.type.update');
        Route::get('studios/types/toggle-status/{type}', 'StudioTypeController@toggleStatus')->name('studio.type.toggle.status');

        ### pages###
        Route::get('page/edit', 'PageController@edit')->name('attendancePage.edit');
        Route::put('page/update/{page}', 'PageController@update')->name('attendancePage.update');

        ###role management routes###
        // Route::resource('role', 'RoleController');
        // Route::get('role/set-permissions/{role}', 'RoleController@setPermissions')->name('role.set-permissions');
        // Route::post('role/set-permissions/update/{role}', 'RoleController@setPermissionsUpdate')->name('role.set-permissions.update');

        ###Sub Admins###
        // Route::get('sub-admin/data', 'SubAdminController@data')->name('sub-admin.data');
        // Route::resource('sub-admin', 'SubAdminController');
        // Route::get('sub-admin/active/{record}', 'SubAdminController@active')->name('sub-admin.active');
        // Route::get('sub-admin/inactive/{record}', 'SubAdminController@inactive')->name('sub-admin.inactive');

        ###Invoices routes###
        Route::get('invoices', 'InvoiceController@index')->name('invoice.index');
        Route::get('invoices/list/data', 'InvoiceController@invoiceList')->name('invoice.list.data');

        Route::get('studio-booking/{id}', 'StudioBookingController@getDetail')->name('studio-booking.detail');





    });
}
);

