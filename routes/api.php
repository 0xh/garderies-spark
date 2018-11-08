<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register the API routes for your application as
| the routes are automatically authenticated using the API guard and
| loaded automatically by this application's RouteServiceProvider.
|
*/

Route::group([
    'middleware' => 'auth:api'
], function () {
});

Route::resource('users', 'API\UserController');

Route::post('users/favorites', 'API\UserController@addToFavorites');

Route::get('nurseries/planning', 'API\NurseryController@planning');
Route::get('nurseries/resources', 'API\NurseryController@resources');
Route::resource('nurseries', 'API\NurseryController');
Route::resource('networks', 'API\NetworkController');
Route::resource('workgroups', 'API\WorkgroupController');

Route::get('availabilities/search', 'API\AvailabilityController@search');
Route::resource('availabilities', 'API\AvailabilityController');
Route::get('availabilities/user/{user}', 'API\AvailabilityController@showForUser')->name('availabilities.showforuser');

Route::resource('bookings', 'API\BookingController');
Route::get('bookings/user/{user}', 'API\BookingController@showForUser')->name('bookings.showforuser');
Route::post('bookings/approve/{booking}', 'API\BookingController@approve')->name('bookings.approve');

Route::resource('booking-requests', 'API\BookingRequestController');
Route::post('booking-requests/approve/{bookingRequest}', 'API\BookingRequestController@approve')->name('booking-requests.approve');

Route::resource('feedbacks', 'API\FeedbackController');
Route::resource('purposes', 'API\PurposeController');
