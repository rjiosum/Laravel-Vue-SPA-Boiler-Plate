<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api\V1\Auth', 'prefix' => 'auth'], function () {

    Route::middleware(['throttle:60,1'])->group(function () {

        Route::middleware(['guest:api'])->group(function () {
            Route::post('register', 'RegisterController@register')->name('register');
            Route::post('login', 'LoginController@login')->name('login');
            Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            Route::post('password/reset', 'ResetPasswordController@reset')->name('password.reset');
        });

        Route::middleware(['auth:api'])->group(function () {
            Route::post('logout', 'LogoutController@logout')->name('logout');
        });

    });

    Route::get('email/verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'VerificationController@resend')->name('verification.resend');

});


