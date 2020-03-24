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

    Route::middleware(['throttle:6,1'])->group(function () {
        Route::middleware(['signed'])->group(function () {
            Route::get('email/verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify');
        });
        Route::post('email/resend', 'VerificationController@resend')->name('verification.resend');
    });

});

Route::group(['namespace' => 'Api\V1\User', 'prefix' => 'user'], function () {

    Route::middleware(['throttle:60,1'])->group(function () {
        Route::get('profile', 'ProfileController@index')->name('profile');
    });

    Route::middleware(['auth:api', 'throttle:5,1'])->group(function () {
        Route::patch('profile/update', 'ProfileController@update')->name('profile.update');
        Route::patch('password/update', 'PasswordController@update')->name('password.update');
        Route::post('avatar/update', 'AvatarController@update')->name('avatar.update');
    });
});

Route::group(['namespace' => 'Api\V1\Article', 'prefix' => 'user', 'middleware' => ['auth:api', 'throttle:60,1']], function (){
    Route::get('article', 'ArticleController@index')->name('article');
    Route::post('article', 'ArticleController@store')->name('article.store');
    Route::get('article/{article}', 'ArticleController@show')->name('article.show');
    Route::delete('article/{article}', 'ArticleController@destroy')->name('article.destroy');
    Route::patch('article/{article}', 'ArticleController@update')->name('article.update');
});
