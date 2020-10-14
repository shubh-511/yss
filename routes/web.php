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
Route::get('login', 'Admin\AdminController@login');
Route::get('/logout', 'Admin\AdminController@logout');
Route::post('login/admin-login', 'Admin\AdminController@adminLogin');

Route::group(['prefix'=>'login','middleware'=>['web','isAdminLogin']], function(){

	Route::get('/dashboard', 'Admin\AdminController@dashboard');

});

	

// Route::group(['middleware'=>['web','isAdminLoggedOut']],function(){
//     Route::any('/login', ['uses'=>'HomeController@login', 'as'=>'admin-login']);
// });
// 	Route::group(['middleware'=>['web','isAdminLoggedOut']],function(){
//     Route::any('/', ['uses'=>'HomeController@login', 'as'=>'admin-login']);
//     Route::any('/login', ['uses'=>'HomeController@login', 'as'=>'admin-login']);
// });
