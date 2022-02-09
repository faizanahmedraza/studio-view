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

    Route::group(['middleware' => 'jwt-auth'], function () {

        ###Page###
        Route::get('page', 'AttendanceUserController@getPage')->name('getPage');

        ###User###
        Route::delete('logout', 'AuthApiController@logout')->name('user-logout');


        // Route::get('attendance/user', 'AttendanceUserController@attendanceMe')->name('attendance-user');
        // Route::post('attendance/user/update', 'AttendanceUserController@attendanceUpdate')->name('attendance-update');
        // Route::post('attendance/change-password', 'AttendanceUserController@changePassword')->name('attendance-change-Password');





    });
});

