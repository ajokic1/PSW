<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['jwt.auth','api-header']], function () {
  
    // all routes to protected resources are registered here  
    Route::post('logout', 'UserController@logout');

    Route::resource('clinics', 'ClinicController');
    

});

Route::group(['middleware' => 'api-header'], function () {
  
    // The registration and login requests doesn't come with tokens 
    // as users at that point have not been authenticated yet
    // Therefore the jwtMiddleware will be exclusive of them
    Route::post('login', 'UserController@login');
    Route::post('register', 'UserController@register');


    Route::get('email/verify/{id}', 'ApiVerificationController@verify')->name('verificationapi.verify');

    Route::get('email/resend', 'ApiVerificationController@resend')->name('verificationapi.resend');
});