<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api\V1\Auth', 'prefix' => 'auth'], function () {

    Route::middleware(['guest:api', 'throttle:60,1'])->group(function () {
        Route::post('register', 'RegisterController@register')->name('api.auth.register');
        Route::post('login', 'LoginController@login')->name('api.auth.login');
    });

});
