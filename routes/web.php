<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$domain = parse_url(config('app.url'));

Route::get('/', 'HomeController@index')->middleware('auth');
Route::view('blog', 'blog');
Route::view('account', 'account');

Route::resource('nurseries', 'NurseryController');
Route::get('nurseries/{nursery}/planning', 'NurseryController@planning')->name('nurseries.planning');
Route::get('nurseries/{nurseries}/ads', 'NurseryController@ads')->name('nurseries.ads');
Route::get('nurseries/{nurseries}/ads/create', 'AdController@create')->name('ads.create');
Route::resource('ads', 'AdController')->except(['index', 'create']);
Route::get('site/{nursery}', 'NurseryController@site');

Route::resource('users', 'UserController');
Route::get('users/{user}/availabilities', 'UserController@availabilities')->name('users.availabilities');
Route::get('users/{user}/bookings', 'UserController@bookings')->name('users.bookings');

Route::get('availabilities/search', 'AvailabilityController@search')->name('availabilities.search');
Route::resource('availabilities', 'AvailabilityController');

Route::resource('bookings', 'BookingController');
Route::resource('booking-requests', 'BookingRequestController');
Route::resource('networks', 'NetworkController');
Route::get('networks/{network}/ads', 'NetworkController@ads')->name('networks.ads');
Route::resource('feedbacks', 'FeedbackController');

Route::get('teams/letter/{user}', 'TeamController@letter')->name('teams.letter');