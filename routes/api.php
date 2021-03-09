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

Route::post('login', 'API\UserController@login');
Route::post('login/with/email', 'API\UserController@loginWithEmail');
Route::post('register', 'API\UserController@register');
Route::get('/get/countries', 'API\CountryController@index');
Route::get('get/access_tokens', 'API\GrantAccessTokenController@index');
Route::post('verify/access_token', 'API\GrantAccessTokenController@verifyAccessToken');
Route::post('import/user', 'API\UserController@importUser');
Route::post('delete/user', 'API\UserController@deleteUser');
Route::post('forgot-password', 'API\UserController@forgotPassword');
Route::post('get/user/status', 'API\UserController@getUserStatus');

//Route::post('verify/otp', 'API\UserController@verifyForgotPasswordOtp');
Route::post('reset/password', 'API\UserController@resetPassword');

Route::get('get/counsellor/packages', 'API\PackageController@getCounsellorPackages');

Route::get('hook/callback', 'API\BookingController@hookCallback');
Route::get('get/counsellor/details', 'API\UserController@counsellorProfile');
Route::post('get/channel', 'API\ChannelController@getChannel');
Route::post('verify/register/account', 'API\UserController@verifyAccount');
Route::get('get/user/notification', 'API\NotificationController@getUserNotification');

Route::middleware('jwt.auth')->get('users', function () {
    return auth('api')->user();
});

Route::get('break-packages', 'API\PackageController@getPackagesWithBreaks');
Route::group(['middleware' => 'jwt.auth'], function(){

	Route::post('profile-update', 'API\UserController@updateProfile');
	
	
	Route::get('getPackagesByCounsellorId', 'API\PackageController@getPackagesByCounsellorId');
	Route::post('create-package', 'API\PackageController@createPackage');
	Route::post('edit-package', 'API\PackageController@editPackage');
	Route::post('delete-package', 'API\PackageController@deletePackage');
	Route::post('add-availability', 'API\AvailabilityController@addAvailability');
	Route::get('get/availability', 'API\AvailabilityController@getAvailability');
	Route::post('update/availability', 'API\AvailabilityController@updateAvailability');
	
	Route::post('change-password', 'API\UserController@changePassword');
	Route::post('update/profile/image', 'API\UserController@updateProfileImage');

	Route::post('update/phone', 'API\UserController@updatePhone');
	Route::post('verify/phone', 'API\UserController@verifyPhone');

	Route::post('make/booking', 'API\BookingController@makeBooking');
	Route::post('confirm/booking', 'API\BookingController@confirmBooking');
	Route::get('view/package', 'API\PackageController@viewPackage');

	
	Route::post('connect/account', 'API\StripeConnectController@connectUserAccount');
	Route::get('get/bookings', 'API\BookingController@getBooking');
	Route::get('get/all/bookings', 'API\BookingController@allBookings');
	

	Route::post('create-channel', 'API\ChannelController@createChannel');

	Route::post('join-session', 'API\ChannelController@joinSession');

	Route::post('accept/or/reject', 'API\ChannelController@acceptOrDecline');

	Route::get('waiting-list', 'API\ChannelController@waitingList');

	Route::post('remove-channel', 'API\ChannelController@removeChannel');
	Route::post('delete/profile/image', 'API\UserController@deleteProfileImage');

	Route::get('get/past/booking', 'API\BookingController@getPastBooking');
	Route::get('get/todays/booking', 'API\BookingController@getTodaysBooking');
	Route::get('get/upcoming/booking', 'API\BookingController@getUpcomingBooking');
	Route::get('get/current/week/booking', 'API\BookingController@getCurrentWeekBooking');
	
	Route::get('get/all/notification', 'API\NotificationController@getAllNotification');
	Route::post('delete/notification', 'API\NotificationController@deleteNotification');
	Route::post('add/slots/to/cart', 'API\BookingController@addSlotsToCart');
	Route::post('delete/slots/from/cart', 'API\BookingController@deleteSlotsFromCart');
	Route::get('get/slots/from/cart', 'API\BookingController@getSlotsFromCart');
	Route::post('logout', 'API\UserController@logout');
	

	Route::get('details', 'API\UserController@details');
});
