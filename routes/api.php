<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api\V1\Auth', 'prefix' => 'auth'], function () {

    Route::middleware(['throttle:60,1'])->group(function () {

        Route::middleware(['guest:api'])->group(function () {
            Route::post('register', 'RegisterController@register')->name('api.auth.register');
            Route::post('login', 'LoginController@login')->name('api.auth.login');
        });

        Route::middleware(['auth:api'])->group(function () {
            Route::post('logout', 'LogoutController@logout')->name('api.auth.logout');
        });

    });

});
