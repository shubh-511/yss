<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Redirect;
use App\Admin\Admins;

class HomeController extends Controller
{
    

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //echo "hello world";
        
        if(Session::has('admin_session')){
            return view('admin/home');
        }else{
            return redirect('admin/login');
        }
        
    }

    public function login(Request $request){

    	if($request->post()){

    		$validator = Validator::make($request->all(), [

    			'email'		=>	'required|string|email|max:255',
    			'password'	=>	'required'

    		]);

    		if($validator->fails()){

    			return Redirect::back()->withErrors($validator)->withInput();
    		}

            
    		$admin = Admins::where('email', $request->email)->first();

    		if($admin){

    			$matched = Hash::check($request->password, $admin->password);

    			if($matched){
                    
    				Session::put('admin_session', $admin);

					return redirect('admin/home');

    			}else{

    				session()->flash("msg-error", "Invalid password provided");
    				return redirect('admin/login');
    			}

    		}else{

    			session()->flash("msg-error", "This email is not registered with us");
    			return redirect('admin/login');
    		}


    	}

    	return view('admin/auth/login');

    }


    public function logout(Request $request){

        if(Session::has('admin_session')){

            session()->forget('admin_session');

            return redirect()->action('Admin\HomeController@login');

        }

    }

}
