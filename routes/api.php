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
Route::post('register', 'API\UserController@register');
Route::get('/get/countries', 'API\CountryController@index');
Route::get('get/access_tokens', 'API\GrantAccessTokenController@index');
Route::post('verify/access_token', 'API\GrantAccessTokenController@verifyAccessToken');
Route::post('import/user', 'API\UserController@importUser');
Route::get('forgot-password', 'API\UserController@forgotPassword');

//Route::post('verify/otp', 'API\UserController@verifyForgotPasswordOtp');
Route::get('reset/password', 'API\UserController@resetPassword');

Route::get('get/counsellor/packages', 'API\PackageController@getCounsellorPackages');



Route::get('breaks-packages', 'API\PackageController@getPackagesWithBreaks');




Route::group(['middleware' => 'auth:api'], function(){
	Route::post('profile-update', 'API\UserController@updateProfile');
	
	
	Route::get('getPackagesByCounsellorId', 'API\PackageController@getPackagesByCounsellorId');
	Route::post('create-package', 'API\PackageController@createPackage');
	Route::post('edit-package', 'API\PackageController@editPackage');
	Route::post('delete-package', 'API\PackageController@deletePackage');
	Route::post('add-availability', 'API\AvailabilityController@addAvailability');
	Route::get('get/availability', 'API\AvailabilityController@getAvailability');
	Route::get('update/availability', 'API\AvailabilityController@updateAvailability');
	
	Route::post('change-password', 'API\UserController@changePassword');
	Route::post('update/profile/image', 'API\UserController@updateProfileImage');

	Route::post('update/phone', 'API\UserController@updatePhone');
	Route::post('verify/phone', 'API\UserController@verifyPhone');

	Route::post('connect/account', 'API\StripeConnectController@connectUserAccount');
	

	Route::get('details', 'API\UserController@details');
});
