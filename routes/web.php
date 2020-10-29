<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();
//Route::get('login', 'Admin\AdminController@login');
Route::get('/logout', 'Admin\AdminController@logout');
Route::post('login/admin-login', 'Admin\AdminController@adminLogin');

Route::group(['prefix'=>'login','middleware'=>['web','isAdminLogin']], function(){

	Route::get('/dashboard', 'Admin\AdminController@dashboard');
	Route::get('/users', 'Admin\UserController@index');
	Route::get('/users/edit/{id}', 'Admin\UserController@edit');
	Route::post('/users/update/{id}', 'Admin\UserController@update');
	Route::get('/users/show/{id}', 'Admin\UserController@show');
	Route::get('/profile', 'Admin\UserController@profile');
	Route::post('/profile/update', 'Admin\UserController@profileUpdate');

	Route::post('/users/destroy/{id?}', 'Admin\UserController@destroy');

	Route::get('/bookings', 'Admin\BookingController@bookingList');

});


