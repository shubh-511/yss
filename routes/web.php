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

Route::group(['prefix'=>'login'], function(){

	//users
	Route::get('/dashboard', 'Admin\AdminController@dashboard');
	Route::get('/users', 'Admin\UserController@index')->middleware(isAdminLogin::class);
	Route::get('/users/edit/{id}', 'Admin\UserController@edit')->middleware(isAdminLogin::class);
	Route::post('/users/update/{id}', 'Admin\UserController@update')->middleware(isAdminLogin::class);
	Route::get('/users/show/{id}', 'Admin\UserController@show')->middleware(isAdminLogin::class);
    Route::get('/users/bulk','Admin\UserController@active')->middleware(isAdminLogin::class);
    Route::get('user/create','Admin\UserController@form')->middleware(isAdminLogin::class);
    Route::post('user/save','Admin\UserController@save')->middleware(isAdminLogin::class);
    
//counsellors
Route::get('/counsellors', 'Admin\CounsellorController@index')->middleware(isAdminLogin::class);
Route::get('/counsellors/create', 'Admin\CounsellorController@create')->middleware(isAdminLogin::class);
Route::post('/counsellors/store', 'Admin\CounsellorController@store')->middleware(isAdminLogin::class);
Route::get('/counsellors/edit/{id}', 'Admin\CounsellorController@edit')->middleware(isAdminLogin::class);
Route::post('/counsellors/update/{id}', 'Admin\CounsellorController@update')->middleware(isAdminLogin::class);
Route::get('/counsellors/show/{id}', 'Admin\CounsellorController@show')->middleware(isAdminLogin::class);
Route::get('/counsellors/bulk','Admin\CounsellorController@active')->middleware(isAdminLogin::class);
Route::get('/counsellors/revenue/{id}', 'Admin\CounsellorController@revenue');
Route::get('/counsellors/list/listedit/{id}','Admin\CounsellorController@listedit');
Route::post('/counsellors/list/update/{id}','Admin\CounsellorController@listupdate');

	//Category
	Route::get('category/create','Admin\CategoryController@create')->middleware(isAdminLogin::class);
	Route::post('category/save','Admin\CategoryController@save')->middleware(isAdminLogin::class);	
    Route::get('category','Admin\CategoryController@categorylist')->middleware(isAdminLogin::class);	
    Route::get('/category/edit/{id}', 'Admin\CategoryController@edit')->middleware(isAdminLogin::class);
    Route::post('/category/update/{id}','Admin\CategoryController@update')->middleware(isAdminLogin::class);
    Route::get('category/destroy/{id?}','Admin\CategoryController@destroy')->middleware(isAdminLogin::class);
    Route::get('category/bulk','Admin\CategoryController@bulkaction')->middleware(isAdminLogin::class);

    //region
    Route::get('region','Admin\RegionController@regionlist')->middleware(isAdminLogin::class);
    Route::get('region/create','Admin\RegionController@create')->middleware(isAdminLogin::class);
    Route::post('region/save','Admin\RegionController@save')->middleware(isAdminLogin::class);
    Route::get('/region/edit/{id}', 'Admin\RegionController@edit')->middleware(isAdminLogin::class);
    Route::post('/region/update/{id}','Admin\RegionController@update')->middleware(isAdminLogin::class);
    Route::get('region/destroy/{id?}','Admin\RegionController@destroy')->middleware(isAdminLogin::class);
    Route::get('region/bulk','Admin\RegionController@bulkaction')->middleware(isAdminLogin::class);

   //label
    Route::get('label','Admin\LabelController@labellist')->middleware(isAdminLogin::class);
    Route::get('label/create','Admin\LabelController@create')->middleware(isAdminLogin::class);
    Route::post('label/save','Admin\LabelController@save')->middleware(isAdminLogin::class);
    Route::get('/label/edit/{id}', 'Admin\LabelController@edit')->middleware(isAdminLogin::class);
    Route::post('/label/update/{id}','Admin\LabelController@update')->middleware(isAdminLogin::class);
    Route::get('label/destroy/{id?}','Admin\LabelController@destroy')->middleware(isAdminLogin::class);
   Route::get('label/bulk','Admin\LabelController@bulkaction')->middleware(isAdminLogin::class);

  
	Route::get('/profile', 'Admin\UserController@profile')->middleware(isAdminLogin::class);
	Route::post('/profile/update', 'Admin\UserController@profileUpdate')->middleware(isAdminLogin::class);

	Route::post('/users/destroy/{id?}', 'Admin\UserController@destroy')->middleware(isAdminLogin::class);
	Route::post('/counsellors/destroy/{id?}', 'Admin\CounsellorController@destroy')->middleware(isAdminLogin::class);

	Route::get('/settings', 'Admin\SettingController@index')->middleware(isAdminLogin::class);
	Route::post('/settings/update-commission', 'Admin\SettingController@updateCommission')->middleware(isAdminLogin::class);

	Route::get('/bookings', 'Admin\BookingController@bookingList');
	Route::get('/bookings/bulk', 'Admin\BookingController@active')->middleware(isAdminLogin::class);
	Route::get('/call-history/{bookingid}', 'Admin\BookingController@callHistory');
	Route::get('/send-notification', 'Admin\SendNotificationController@sendNotification')->middleware(isAdminLogin::class);
	Route::post('/send', 'Admin\SendNotificationController@send')->middleware(isAdminLogin::class);

	Route::get('/tickets', 'Admin\TicketController@getTickets')->middleware(isAdminLogin::class);
	Route::get('/tickets/detail/{id}', 'Admin\TicketController@getTicketDetail')->middleware(isAdminLogin::class);

	Route::get('/tickets/refund-ticket/{ticketId}', 'Admin\TicketController@refundTicket')->middleware(isAdminLogin::class);
    Route::get('/tickets/bulk', 'Admin\TicketController@active')->middleware(isAdminLogin::class);

	Route::get('/create-booking', 'Admin\BookingController@createBooking')->middleware(isAdminLogin::class);
	Route::get('/counsellor/select-package/{counsellorId}', 'Admin\BookingController@getAllPackages')->middleware(isAdminLogin::class);

	Route::get('/listings', 'Admin\AdminListingController@getListings')->middleware(isAdminLogin::class);
	Route::get('/listingStatus', 'Admin\AdminListingController@listingStatus')->middleware(isAdminLogin::class);
	Route::get('/listings/detail/{listingid}', 'Admin\AdminListingController@getListingDetails')->middleware(isAdminLogin::class);

	Route::get('/getCounsellorPackage', 'Admin\BookingController@getCounsellorPackage')->middleware(isAdminLogin::class);
	Route::get('/getSlotsByDate', 'Admin\BookingController@getSlotsByDate')->middleware(isAdminLogin::class);
	Route::post('/make/custom/booking', 'Admin\BookingController@makeCustomBooking')->middleware(isAdminLogin::class);


	Route::get('insurance','Admin\InsuranceLeadController@insurancelist')->middleware(isAdminLogin::class);
	Route::get('insurance/bulk','Admin\InsuranceLeadController@bulkaction')->middleware(isAdminLogin::class);
	Route::get('insurance/destroy/{id?}','Admin\InsuranceLeadController@destroy')->middleware(isAdminLogin::class);
	Route::get('insurance/view/{id?}','Admin\InsuranceLeadController@view')->middleware(isAdminLogin::class);
});
