<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth', 'namespace' => 'Auth', 'middleware' => ['api.request.type']], function () {

    Route::post('/register/step/{step}', ['as' => 'register', 'uses' => 'RegisterController@stepRegister']);
    Route::get('/verify-email/{token}', ['as' => 'verify.email', 'uses' => 'VerifyEmailController@verify']);
    Route::post('/resend-verification', ['as' => 'resend.verification', 'uses' => 'VerifyEmailController@resend']);
    Route::post('/login', ['as' => 'login', 'uses' => 'LoginController@login']);

    Route::post('/forget-password', ['as' => 'forgot', 'uses' => 'ForgotPasswordController@forgot']);
    Route::post('/reset-password/{token}', ['as' => 'updatePassword', 'uses' => 'ResetPasswordController@updatePassword']);
    Route::post('/verification', ['as' => 'verification', 'uses' => 'VerificationController@confirmCode']);
    Route::get('/google', ['as' => 'google', 'uses' => 'SocialiteController@redirectToGoogle']);
    Route::get('/facebook', ['as' => 'facebook', 'uses' => 'SocialiteController@redirectToFacebook']);
    Route::post('/google/callback', ['as' => 'google_callback', 'uses' => 'SocialiteController@handleGoogleCallback']);
    Route::post('/facebook/callback', ['as' => 'facebook_callback', 'uses' => 'SocialiteController@handleFacebookCallback']);

});

Route::post('/auth/refresh', ['uses' => 'Auth\TokenRefreshController@refresh', 'middleware' => ['api.request.type']]);

Route::post('/logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout', 'middleware' => ['api.auth']]);


