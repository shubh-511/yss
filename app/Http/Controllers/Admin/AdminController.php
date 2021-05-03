<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User; 
use App\Booking;
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
                'role_id' => [1,2],
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
        $bookingCount = Booking::where('status', '1')->count();

        //return $request;
        $users = User::select(\DB::raw("COUNT(*) as count"))
                    ->whereYear('created_at', date('Y'))
                    ->groupBy(\DB::raw("Month(created_at)"))
                    ->pluck('count');

        $bookings = Booking::select(\DB::raw("COUNT(*) as count"))
                    ->whereYear('created_at', date('Y'))
                    ->groupBy(\DB::raw("Month(created_at)"))
                    ->pluck('count');            

        return view('admin.home',compact('userCount','bookingCount','users','bookings'));
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
