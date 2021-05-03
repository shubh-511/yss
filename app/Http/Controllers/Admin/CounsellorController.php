<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Booking;
use App\Package;
use DB;
use Auth;
use Hash;
use Event;
use Validator;
use App\Events\CounsellorRegisterEvent;

class CounsellorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::where(function ($query) use($request) {
        if ($request->get('status') != null) { 
        $query->where('account_enabled',$request->get('status'));
        } 
        if ($request->get('email') != null) {
        $query->where('name', 'like', '%' . $request->get('email') . '%')
        ->orwhere('email', 'like', '%' . $request->get('email') . '%');
        }
       if ($request->get('email') != null && $request->get('status') != null)
       {
         $query->where('name', 'like', '%' . $request->get('email') . '%')
        ->orwhere('email', 'like', '%' . $request->get('email') . '%')
        ->where('account_enabled',$request->get('status'));
    
       } 
      })->where('role_id','=',2)->orderBy('id','DESC')->paginate(25);
        return view('admin.counsellor.index',compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.counsellor.add');
    }

      public function active(Request $request)
    {
       $data=$request['action'];
       $id=$request['id'];
       if($data=="active")
       {
        $user_data=User::whereIn('id', $id)
       ->update(['account_enabled' => '1']);
       return response()->json(array('message' => 'success'));
       }
       else if($data=="disabled")
       {
        $user_data=User::whereIn('id', $id)
       ->update(['account_enabled' => '0']);
       return response()->json(array('message' => 'success'));
      }
        else if($data=="verification")
       {
        $user_data=User::whereIn('id', $id)
       ->update(['account_enabled' => '3']);
       return response()->json(array('message' => 'success'));
       }
        else
       {
          $data=\App\User::whereIn('id',$id)->delete();
          return response()->json(array('message' => 'success'));
       }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            $validator =  Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'timezone' => 'required',
            ]);

            if ($validator->fails()) 
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $counsellor = new User;
            $counsellor->name = ucwords(strtolower($request->name));
            $counsellor->email = strtolower($request->email);
            $counsellor->password = bcrypt($request->password);
            $counsellor->timezone = $request->timezone;
            $counsellor->role_id = '2';
            $counsellor->account_enabled = '1';
            $counsellor->save();

            event(new CounsellorRegisterEvent($counsellor->id, $request->password));

            return redirect('login/counsellors')->with('success','Counsellor added successfully');
        }
        catch(\Exception $e)
        {
            return redirect()->back()->with('err_message','Something went wrong!');
        }
        
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $user = User::find($id);
        return view('admin.counsellor.show',compact('user'));
    }
    public function revenue(Request $request,$id)
    {
        if ($request->get('year') != null && $request->get('month') != null) {

        $month_data=DB::table('bookings')
        ->Join('payments','payments.id','=','bookings.payment_id')
        ->where('counsellor_id',$id)
        ->whereYear('booking_date', '=', $request->get('year'))
        ->whereMonth('booking_date', '=', $request->get('month'))
        ->select(DB::raw('month(booking_date)'))
        ->groupBy(DB::raw('month(booking_date)'))
        ->get();
         $array=$month_data->toArray();
            $month_array=json_decode( json_encode($array), true);
            $mon[]='';
            foreach ($month_array as $k=>$obj)
        {

            $mon[]=date("F", mktime(0, 0, 0, $obj['month(booking_date)'], 1));
        }
             $mon_result = array_diff_key($mon,array_flip((array) ['0']));
             $users_mon_data=array_values($mon_result);
              if($users_mon_data==array())
             {
                return view('admin.counsellor.blankrevenue');
             }
            $u_data=DB::table('bookings')
            ->Join('payments','payments.id','=','bookings.payment_id')
            ->where('counsellor_id',$id)
            ->whereYear('booking_date', '=', $request->get('year'))
            ->whereMonth('booking_date', '=', $request->get('month'))
            ->select(DB::raw('SUM(payments.amount)'))
            ->groupBy(DB::raw('month(booking_date)'))
            ->get();
            $array=$u_data->toArray();
            $array1=json_decode( json_encode($array), true);
            $data[]='';
            foreach ($array1 as $k=>$obj)
        {

            $data[]=$obj['SUM(payments.amount)'];
        }
             $result = array_diff_key($data,array_flip((array) ['0']));
             $users_data=array_values($result);
             return view('admin.counsellor.revenue',compact('users_data','users_mon_data'));
            }     
         else if ($request->get('year') != null) {

            $month_data=DB::table('bookings')
        ->Join('payments','payments.id','=','bookings.payment_id')
        ->where('counsellor_id',$id)
        ->where('booking_date', 'like', '%' . $request->get('year') . '%')
        ->select(DB::raw('month(booking_date)'))
        ->groupBy(DB::raw('month(booking_date)'))
        ->get();
         $array=$month_data->toArray();
            $month_array=json_decode( json_encode($array), true);
            $mon[]='';
            foreach ($month_array as $k=>$obj)
        {

            $mon[]=date("F", mktime(0, 0, 0, $obj['month(booking_date)'], 1));
        }
            $mon_result = array_diff_key($mon,array_flip((array) ['0']));
             $users_mon_data=array_values($mon_result);
             if($users_mon_data==array())
             {
                return view('admin.counsellor.blankrevenue');
             }
             
            $u_data=DB::table('bookings')
            ->Join('payments','payments.id','=','bookings.payment_id')
            ->where('counsellor_id',$id)
            ->where('booking_date', 'like', '%' . $request->get('year') . '%')
            ->select(DB::raw('SUM(payments.amount)'))
            ->groupBy(DB::raw('month(booking_date)'))
            ->get();
            $array=$u_data->toArray();
            $array1=json_decode( json_encode($array), true);
            $data[]='';
            foreach ($array1 as $k=>$obj)
        {

            $data[]=$obj['SUM(payments.amount)'];
        }
             $result = array_diff_key($data,array_flip((array) ['0']));
             $users_data=array_values($result);
             return view('admin.counsellor.revenue',compact('users_data','users_mon_data'));
            }     

         
         else
         {

         $month_data=DB::table('bookings')
        ->Join('payments','payments.id','=','bookings.payment_id')
        ->where('counsellor_id',$id)
        ->select(DB::raw('month(booking_date)'))
        ->groupBy(DB::raw('month(booking_date)'))
        ->get();
         $array=$month_data->toArray();
            $month_array=json_decode( json_encode($array), true);
            $mon[]='';
            foreach ($month_array as $k=>$obj)
        {

            $mon[]=date("F", mktime(0, 0, 0, $obj['month(booking_date)'], 1));
        }
             $mon_result = array_diff_key($mon,array_flip((array) ['0']));
             $users_mon_data=array_values($mon_result);
    
            $u_data=DB::table('bookings')
            ->Join('payments','payments.id','=','bookings.payment_id')
            ->where('counsellor_id',$id)
            ->select(DB::raw('SUM(payments.amount)'))
            ->groupBy(DB::raw('month(booking_date)'))
            ->get();
            $array=$u_data->toArray();

            $array1=json_decode( json_encode($array), true);
            $data[]='';
            foreach ($array1 as $k=>$obj)
        {

            $data[]=$obj['SUM(payments.amount)'];
        }
         $result = array_diff_key($data,array_flip((array) ['0']));
         $users_data=array_values($result);
             return view('admin.counsellor.revenue',compact('users_data','users_mon_data'));
            }        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile()
    { 
        $user = Auth::user();
        return view('admin.users.profile',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profileUpdate(Request $request)
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            //'email' => 'required|email|unique:users,email,'.$id,
        ]);

        if ($validator->fails()) 
        { 
            return redirect()->back()->with('err_message',$validator->messages()->first());
        }

        $user = Auth::user();
        //return $user;
        $user->name = $request->name;
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->save();

        return redirect('login/profile')->with('success', 'Successfully updated');
        
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $userRole = $user->roles->pluck('name','name')->all();
        return view('admin.counsellor.edit',compact('user','userRole'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            //'email' => 'required|email|unique:users,email,'.$id,
        ]);

        if ($validator->fails()) 
        { 
            return redirect()->back()->with('err_message',$validator->messages()->first());
        }

        $user = User::find($id);
        //return $user;
        $user->name = $request->name;
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->save();

        return redirect('login/counsellors')->with('success', 'Successfully updated');
        
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id='')
    {
        //echo $request->id; die;
        User::where('id', $request->id)->delete();
        return redirect('login/counselors')->with('success','Counsellor deleted successfully');
    }
}