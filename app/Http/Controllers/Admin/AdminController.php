<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 
use Validator;
//use App\Events\UserRegisterEvent;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /***
    login page
    ***/
    public function login()
    { 
        if(Auth::check())
        {
            return redirect('login/dashboard');
        }
        return view('admin.auth.login');
    }


    /***
    login check
    ***/
    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'email' => 'required', 
            'password' => 'required',
        ]);

        if ($validator->fails()) 
        { 
            return redirect()->back()->with('err_message',$validator->messages()->first());
        }

        if(Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
                'role_id' => 1,
            ]))
        {
            return redirect('login/dashboard');
        } 
        else
        {
            return redirect()->back()->with('err_message','Invalid email or password');
        }
        
    }

    /***
    dashboard
    ***/
    public function dashboard(Request $request)
    {
        $userCount = User::where('role_id','!=',1)->count();
        return view('admin.home',compact('userCount'));
    }


    /***
    logout
    ***/
    public function logout(Request $request)
    {
        Auth::logout();
        return Redirect('login');
    }

}
