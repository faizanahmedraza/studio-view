<?php


Route::group(['prefix' => 'v1'], static function () {
    Route::get('test', static function () {
        $token = jwt()->attempt(['email' => 'admin@studio.com', 'password' => 'admin123']);//fromUser(\App\Models\User::getUserById(6));
        dd($token);
    });

    ###User###
    Route::post('sign-up', 'AuthApiController@userRegister')->name('user-sign-up');
    Route::post('login', 'AuthApiController@userLogin')->name('user-login');
    Route::post('forgot-password', 'AuthApiController@ForgotPassword')->name('Forgot-Password');
    Route::post('login-fb', 'AuthApiController@userLoginFb')->name('user-login');
    Route::post('login-google', 'AuthApiController@userLoginGoogle')->name('user-login');
    // Route::post('login-icloud', 'AuthApiController@userLoginIcloud')->name('user-login');

    // get all studios by location search
    Route::get('studios/search', 'StudioController@search');


    Route::group(['middleware' => 'jwt-auth'], function () {

        ###Page###
        // Route::get('page', 'UserController@getPage')->name('getPage');

        ###User###
        Route::delete('logout', 'AuthApiController@logout')->name('user-logout');
        Route::get('customer', 'UserController@getCustomer')->name('get-customer');
        Route::put('customer/update', 'UserController@customerUpdate')->name('customer-update');
        Route::put('customer/change-password', 'UserController@changePassword')->name('customer-change-Password');

        ###Advance booking time###
        Route::get('advance-booking-list', 'BookingTimeController@index');

        ###Types###
        Route::get('types-list', 'TypeController@index');

        ###Studio Cities Api
        Route::get('studios/cities/list', 'StudioController@citiesList');
        ###Studio###
        Route::get('studios/list', 'StudioController@index');
        Route::resource('studios', 'StudioController');

        ###Sms###
        Route::get('send-verification-sms', 'SMSController@sendSms');
        Route::post('verify-sms-code', 'SMSController@verifySmsCode');

         ###Studio Request###
         Route::post('studio-request', 'StudioRequestController@request');

         ###Customer Saved Apis###
        Route::get('studios/saved/list', 'CustomerFavouriteController@index');
        Route::post('studios/saved', 'CustomerFavouriteController@store');
        Route::delete('studios/saved/{studio_id}', 'CustomerFavouriteController@destroy');

        ###Send Notification Apis###
        Route::post('/send-notification', 'NotificationController@sendNotification')->name('send.notification');
    });

    //public api of studios listing
    Route::get('/studios/public/list', 'StudioController@studiosList');
});

