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
    //return view('welcome');
    return redirect('public/login');
});

//Auth::routes();
Route::get('login', 'Admin\AdminController@login');
Route::get('/logout', 'Admin\AdminController@logout');
Route::post('login/admin-login', 'Admin\AdminController@adminLogin');

Route::group(['prefix'=>'login','middleware'=>['web','isAdminLogin']], function(){

	//users
	Route::get('/dashboard', 'Admin\AdminController@dashboard');
	Route::get('/users', 'Admin\UserController@index');
	Route::get('/users/edit/{id}', 'Admin\UserController@edit');
	Route::post('/users/update/{id}', 'Admin\UserController@update');
	Route::get('/users/show/{id}', 'Admin\UserController@show');
    Route::get('/users/bulk','Admin\UserController@active');
    Route::get('user/create','Admin\UserController@form');
    Route::post('user/save','Admin\UserController@save');
    



	//counsellors
	Route::get('/counsellors', 'Admin\CounsellorController@index');
	Route::get('/counsellors/create', 'Admin\CounsellorController@create');
	Route::post('/counsellors/store', 'Admin\CounsellorController@store');
	Route::get('/counsellors/edit/{id}', 'Admin\CounsellorController@edit');
	Route::post('/counsellors/update/{id}', 'Admin\CounsellorController@update');
	Route::get('/counsellors/show/{id}', 'Admin\CounsellorController@show');
	 Route::get('/counsellors/bulk','Admin\CounsellorController@active');
	Route::get('/counsellors/revenue/{id}', 'Admin\CounsellorController@revenue');

	Route::get('/profile', 'Admin\UserController@profile');
	Route::post('/profile/update', 'Admin\UserController@profileUpdate');

	Route::post('/users/destroy/{id?}', 'Admin\UserController@destroy');
	Route::post('/counsellors/destroy/{id?}', 'Admin\CounsellorController@destroy');

	Route::get('/settings', 'Admin\SettingController@index');
	Route::post('/settings/update-commission', 'Admin\SettingController@updateCommission');

	Route::get('/bookings', 'Admin\BookingController@bookingList');
	Route::get('/bookings/bulk', 'Admin\BookingController@active');
	Route::get('/call-history/{bookingid}', 'Admin\BookingController@callHistory');
	Route::get('/send-notification', 'Admin\SendNotificationController@sendNotification');
	Route::post('/send', 'Admin\SendNotificationController@send');

	Route::get('/tickets', 'Admin\TicketController@getTickets');
	Route::get('/tickets/detail/{id}', 'Admin\TicketController@getTicketDetail');

	Route::get('/tickets/refund-ticket/{ticketId}', 'Admin\TicketController@refundTicket');
    Route::get('/tickets/bulk', 'Admin\TicketController@active');

	Route::get('/create-booking', 'Admin\BookingController@createBooking');
	Route::get('/counsellor/select-package/{counsellorId}', 'Admin\BookingController@getAllPackages');

	Route::get('/listings', 'Admin\AdminListingController@getListings');
	Route::get('/listingStatus', 'Admin\AdminListingController@listingStatus');
	Route::get('/listings/detail/{listingid}', 'Admin\AdminListingController@getListingDetails');

	Route::get('/getCounsellorPackage', 'Admin\BookingController@getCounsellorPackage');
	Route::get('/getSlotsByDate', 'Admin\BookingController@getSlotsByDate');
	Route::post('/make/custom/booking', 'Admin\BookingController@makeCustomBooking');


	Route::get('insurance','Admin\InsuranceLeadController@insurancelist');
	Route::get('insurance/bulk','Admin\InsuranceLeadController@bulkaction');
	Route::get('insurance/destroy/{id?}','Admin\InsuranceLeadController@destroy');
	Route::get('insurance/view/{id?}','Admin\InsuranceLeadController@view');


});


