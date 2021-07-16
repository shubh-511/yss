<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Role; 
use App\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\Traits\CheckPermission;
use App\Payment;
//use App\Events\UserRegisterEvent;

class AdminController extends Controller
{
    use CheckPermission;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    /***
    login page
    ***/
    public function login()
    { 
        if(Auth::check())
        {
            $roleId = Auth::user()->role_id;
            if($roleId == 1)
            {
              return redirect('login/dashboard');
            }
            else
            {
                $module_name=$this->permission(Auth::user()->id);
                return view('admin.dashboard',compact('module_name'));
            }
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
         $role_id=Role::whereNotIn('id',['3'])->pluck('id')->toArray();
        if(Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
                'role_id' =>"1",
            ]))
        {
            return redirect('login/dashboard');
        } 
        elseif(Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
                'role_id' => $role_id,
            ]))
        {
            $module_name=$this->permission(Auth::user()->id);
            return view('admin.dashboard',compact('module_name'));
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
        $id=Auth::user()->role_id;
        if($id == 2)
        {
            $bookingCount = Booking::where('status', '1')->where('counsellor_id',Auth::user()->id)->count();
            $bookings = Booking::select(\DB::raw("COUNT(*) as count"))
                        ->whereMonth('booking_date', Carbon::now()->month)
                        ->where('counsellor_id',Auth::user()->id)
                    ->pluck('count'); 
                    $date = \Carbon\Carbon::now();
             $month_name=$date->format('F');
             $module_name=$this->permission(Auth::user()->id);
            return view('admin.counsellor.home',compact('bookingCount','bookings','month_name','module_name'));

        }
        $userCount = User::where('role_id','!=',1)->count();
        $bookingCount = Booking::where('status', '1')->count();

        //return $request;
        $Second_month = Carbon::now()->startOfMonth()->subMonth(1);
        $first_month = Carbon::now();
        $users = User::select(\DB::raw("COUNT(*) as count"))
                    ->whereYear('created_at', date('Y'))
                    ->whereBetween('created_at',[$Second_month,$first_month])
                    ->groupBy(\DB::raw("Month(created_at)"))
                    ->pluck('count');
        $users_month_value = User::whereYear('created_at', date('Y'))
                    ->whereBetween('created_at',[$Second_month,$first_month])->get();
        $mon_data=$users_month_value->pluck('created_at','id')->map->format('F')->unique();
        $mon[]='';
         foreach ($mon_data as $k=>$obj)
                {

                    $mon[]=$obj;
                }
        $mon_result = array_diff_key($mon,array_flip((array) ['0']));
        $users_mon_data=array_values($mon_result);
        $bookings = Booking::select(\DB::raw("COUNT(*) as count"))
                    ->whereYear('created_at', date('Y'))
                    ->whereBetween('created_at',[$Second_month,$first_month])
                    ->groupBy(\DB::raw("Month(created_at)"))
                    ->pluck('count');            
       $booking_month_value = Booking::whereYear('created_at', date('Y'))
                    ->whereBetween('created_at',[$Second_month,$first_month])->get();
        $booking_mon_data=$booking_month_value->pluck('created_at','id')->map->format('F')->unique();
        $bookin_mon[]='';
         foreach ($booking_mon_data as $k=>$obj)
                {

                    $bookin_mon[]=$obj;
                }
        $booking_mon_result = array_diff_key($bookin_mon,array_flip((array) ['0']));
        $booking_data=array_values($booking_mon_result);
        $module_name=$this->permission(Auth::user()->id);
        $total_revenue = Payment::sum('amount');
        return view('admin.home',compact('userCount','bookingCount','users','bookings','users_mon_data','booking_data','module_name','total_revenue'));
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
