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
	Route::get('/users', 'Admin\UserController@index');
	Route::get('/users/edit/{id}', 'Admin\UserController@edit');
	Route::post('/users/update/{id}', 'Admin\UserController@update');
	Route::get('/users/show/{id}', 'Admin\UserController@show');
    Route::get('/users/bulk','Admin\UserController@active');
    Route::get('user/create','Admin\UserController@form');
    Route::post('user/save','Admin\UserController@save');
    Route::get('download/user','Admin\UserController@download');
    //Role
    Route::get('/role', 'Admin\RoleController@role');
	Route::get('/role/create', 'Admin\RoleController@createRole');
	Route::post('/role/save', 'Admin\RoleController@saveRole');
	Route::get('/role/edit/{id}', 'Admin\RoleController@editRole');
	Route::post('/role/update/{id}', 'Admin\RoleController@updateRole');
	Route::get('/role/privilege', 'Admin\RoleController@rolePrivilege');
	Route::get('create/privilege', 'Admin\RoleController@savePrivilege');
	Route::post('/save/privilege', 'Admin\RoleController@storePrivilege');
	Route::get('/edit/previlege/{id}', 'Admin\RoleController@editPrivilege');
	Route::post('/update/privilege', 'Admin\RoleController@updatePrivilege');
	Route::get('/previlege/destroy', 'Admin\RoleController@destroy');

    //Module
    Route::get('/module', 'Admin\ModuleController@module');
    Route::get('/module/create', 'Admin\ModuleController@createModule');
    Route::post('/module/save', 'Admin\ModuleController@saveModule');
    Route::get('/module/edit/{id}', 'Admin\ModuleController@editModule');
	Route::post('/module/update/{id}', 'Admin\ModuleController@updateModule');
    
//counsellors
Route::get('/counsellors', 'Admin\CounsellorController@index');
Route::get('/counsellors/create', 'Admin\CounsellorController@create');
Route::post('/counsellors/store', 'Admin\CounsellorController@store');
Route::get('/counsellors/edit/{id}', 'Admin\CounsellorController@edit');
Route::post('/counsellors/update/{id}', 'Admin\CounsellorController@update');
Route::get('/counsellors/show/{id}', 'Admin\CounsellorController@show');
Route::get('/counsellors/bulk','Admin\CounsellorController@active');
Route::get('/counsellors/revenue/{id}', 'Admin\CounsellorController@revenue');
Route::get('/counsellors/list/listedit/{id}','Admin\CounsellorController@listedit');
Route::post('/counsellors/list/update/{id}','Admin\CounsellorController@listupdate');
Route::get('download/counsellor','Admin\CounsellorController@download');

//SubAdmin
Route::get('sub/admin','Admin\SubAdminController@subAdmin');
Route::get('sub/admin/destroy','Admin\SubAdminController@destroy');
Route::get('sub/admin/bulk','Admin\SubAdminController@bulk');
Route::get('sub/admin/edit/{id}','Admin\SubAdminController@edit');
Route::post('sub/admin/update/{id}','Admin\SubAdminController@update');
	//Category
	Route::get('category/create','Admin\CategoryController@create');
	Route::post('category/save','Admin\CategoryController@save');	
    Route::get('category','Admin\CategoryController@categorylist');	
    Route::get('/category/edit/{id}', 'Admin\CategoryController@edit');
    Route::post('/category/update/{id}','Admin\CategoryController@update');
    Route::get('category/destroy/{id?}','Admin\CategoryController@destroy');
    Route::get('category/bulk','Admin\CategoryController@bulkaction');
   Route::get('download/category','Admin\CategoryController@download');

    //region
    Route::get('region','Admin\RegionController@regionlist');
    Route::get('region/create','Admin\RegionController@create');
    Route::post('region/save','Admin\RegionController@save');
    Route::get('/region/edit/{id}', 'Admin\RegionController@edit');
    Route::post('/region/update/{id}','Admin\RegionController@update');
    Route::get('region/destroy/{id?}','Admin\RegionController@destroy');
    Route::get('region/bulk','Admin\RegionController@bulkaction');
    Route::get('download/region','Admin\RegionController@download');

   //label
    Route::get('label','Admin\LabelController@labellist');
    Route::get('label/create','Admin\LabelController@create');
    Route::post('label/save','Admin\LabelController@save');
    Route::get('/label/edit/{id}', 'Admin\LabelController@edit');
    Route::post('/label/update/{id}','Admin\LabelController@update');
    Route::get('label/destroy/{id?}','Admin\LabelController@destroy');
    Route::get('label/bulk','Admin\LabelController@bulkaction');
    Route::get('download/label','Admin\LabelController@download');

  
	Route::get('/profile', 'Admin\UserController@profile');
	Route::post('/profile/update', 'Admin\UserController@profileUpdate');

	Route::post('/users/destroy/{id?}', 'Admin\UserController@destroy');
	Route::post('/counsellors/destroy/{id?}', 'Admin\CounsellorController@destroy');

	Route::get('/settings', 'Admin\SettingController@index');
	Route::post('/settings/update-commission', 'Admin\SettingController@updateCommission');

	Route::get('/bookings', 'Admin\BookingController@bookingList');
	Route::get('/bookings/bulk', 'Admin\BookingController@active');
	Route::get('/download/bookings', 'Admin\BookingController@download');
	Route::get('/call-history/{bookingid}', 'Admin\BookingController@callHistory');
	Route::get('/download/report/{bookingid}', 'Admin\BookingController@downloadreport');
	Route::get('/send-notification', 'Admin\SendNotificationController@sendNotification');
        Route::get('/sendnoti', 'Admin\SendNotificationController@sendnoti');
	Route::post('/send', 'Admin\SendNotificationController@send');

	Route::get('/tickets', 'Admin\TicketController@getTickets');
	Route::get('/tickets/detail/{id}', 'Admin\TicketController@getTicketDetail');

	Route::get('/tickets/refund-ticket/{ticketId}', 'Admin\TicketController@refundTicket');
        Route::get('/tickets/bulk', 'Admin\TicketController@active');

	Route::get('/create-booking', 'Admin\BookingController@createBooking');
	Route::get('/counsellor/select-package/{counsellorId}', 'Admin\BookingController@getAllPackages');

	Route::get('/listings', 'Admin\AdminListingController@getListings');
	Route::get('/listingStatus', 'Admin\AdminListingController@listingStatus');
	Route::get('/reject/listingStatus', 'Admin\AdminListingController@rejectListingStatus');
	Route::get('/listings/detail/{listingid}', 'Admin\AdminListingController@getListingDetails');
	Route::get('/listings/bulk/', 'Admin\AdminListingController@bulk');
	 Route::get('download/listing','Admin\AdminListingController@download');

	Route::get('/getCounsellorPackage', 'Admin\BookingController@getCounsellorPackage');
	Route::get('/getSlotsByDate', 'Admin\BookingController@getSlotsByDate');
	Route::post('/make/custom/booking', 'Admin\BookingController@makeCustomBooking');


	Route::get('insurance','Admin\InsuranceLeadController@insurancelist');
	Route::get('insurance/bulk','Admin\InsuranceLeadController@bulkaction');
	Route::get('insurance/destroy/{id?}','Admin\InsuranceLeadController@destroy');
	Route::get('insurance/view/{id?}','Admin\InsuranceLeadController@view');
	Route::get('/transaction','Admin\TransactionController@transactionlist');
	Route::get('/transaction/download','Admin\TransactionController@download');
	
});
