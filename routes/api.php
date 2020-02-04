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
    Route::resource('appointment_types', 'AppointmentTypeController');
    Route::resource('appointments', 'AppointmentController');
    Route::resource('diagnoses', 'DiagnosisController');

    //Route::get('clinics/{clinic}/doctors/{doctor}/availability/{date}/{duration}',
    //    'DoctorController@availability');

    Route::get('availability/date/{date}',
        'AvailabilityController@get');

    Route::get('appointments/details/{doctor}/{clinic}/{appointment_type}/{date}',
        'AppointmentController@details');
    Route::get('appointments/{appointment}/decline/{token}',
        'AppointmentController@decline');

    Route::get('user/{user}/appointments', 'AppointmentController@index');
    Route::get('user/{user}/appointments/history', 'AppointmentController@indexHistory');
    Route::post('user/update', 'UserController@update');

    Route::post('doctors/{doctor}/rate', 'DoctorRatingController@rate');
    Route::get('doctors/{doctor}/rating', 'DoctorRatingController@rating');

    Route::post('clinics/{clinic}/rate', 'ClinicRatingController@rate');
    Route::get('clinics/{clinic}/rating', 'ClinicRatingController@rating');
});

Route::group(['middleware' => 'api-header'], function () {

    // The registration and login requests doesn't come with tokens
    // as users at that point have not been authenticated yet
    // Therefore the jwtMiddleware will be exclusive of them
    Route::post('login', 'UserController@login');
    Route::post('register', 'UserController@register');

    Route::get('appointments/{appointment}/accept/{token}',
        'AppointmentController@accept');

    Route::get('appointments/{appointment}/decline/{token}',
        'AppointmentController@decline');

    Route::get('email/verify/{id}', 'ApiVerificationController@verify')
        ->name('verificationapi.verify');

    Route::get('email/resend', 'ApiVerificationController@resend')
        ->name('verificationapi.resend');
});
