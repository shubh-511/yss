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
Route::post('forgot-password', 'API\UserController@forgotPassword');

//Route::post('verify/otp', 'API\UserController@verifyForgotPasswordOtp');
Route::post('reset/password', 'API\UserController@resetPassword');




Route::group(['middleware' => 'auth:api'], function(){
	Route::post('profile-update', 'API\UserController@updateProfile');

	Route::post('getPackagesByCounsellorId', 'API\PackageController@getPackagesByCounsellorId');
	Route::post('create-package', 'API\PackageController@createPackage');
	Route::post('edit-package', 'API\PackageController@editPackage');
	Route::post('delete-package', 'API\PackageController@deletePackage');
	Route::post('add-availability', 'API\AvailabilityController@addAvailability');
	Route::post('update/profile/image', 'API\UserController@updateProfileImage');

	Route::post('details', 'API\UserController@details');
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
