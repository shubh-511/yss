<?php


namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Booking;
use App\Availability;
use App\Listing;
use App\Package;
use App\Payment;
use App\StripeConnect;
use App\UserTickets;
use App\Role;
use App\Module;
use App\RoleModule;
use DB;
use Auth;
use Hash;
use Validator;
use App\Events\CounsellorRegisterEvent;
use App\GeneralSetting;
use App\Traits\CheckPermission;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class UserController extends Controller
{
      use CheckPermission;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $module_name=$this->permission(Auth::user()->id);
        $general_setting= GeneralSetting::where('id','=',1)->first();
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
      })->where('role_id','=',3)->orderBy('id','DESC')->paginate($general_setting->pagination_value);
        return view('admin.users.index',compact('users','module_name'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $module_name=$this->permission(Auth::user()->id);
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles','module_name'));
    }
    public function form()
    {
     $module_name=$this->permission(Auth::user()->id);   
     $role_data=Role::whereNotIn('role',['counsellor'])->get();
     $module_data=Module::get();
     return view('admin.users.add',compact('role_data','module_data','module_name'));
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);


        $input = $request->all();
        $input['password'] = Hash::make($input['password']);


        $user = User::create($input);
        $user->assignRole($request->input('roles'));


        return redirect('login/users')->with('success','User created successfully');
    }

     public function save(Request $request)
     {
        try
        {
            $validator =  Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => ['required','same:confirm-password','min:8','regex:/[0-9]/','regex:/[@$!%*#?&]/',],
            'timezone' => 'required',
            'role'=>  'required',
            ],['password.regex' => 'Password must contain 8 characters including 1 special character and 1 numeric character.','password.min' => 'Password must contain 8 characters including 1 special character and 1 numeric character.',]);

            if ($validator->fails()) 
            {

                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user_data = new User;
            $user_data->name = ucwords(strtolower($request->name));
            $user_data->email = strtolower($request->email);
            $user_data->password = bcrypt($request->password);
            $user_data->timezone = $request->timezone;
            $user_data->role_id = $request->role;
            $user_data->account_enabled = '1';
            $user_data->save();
            event(new CounsellorRegisterEvent($user_data->id, $request->password));

            return redirect('login/users')->with('success','User added successfully');
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
        $module_name=$this->permission(Auth::user()->id);
        $user = User::find($id);
        return view('admin.users.show',compact('user','module_name'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile()
    { 
        $module_name=$this->permission(Auth::user()->id);
        $user = Auth::user();
        return view('admin.users.profile',compact('user','module_name'));
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
            ]);
         if ($validator->fails()) 
        { 
            return redirect()->back()->with('err_message',$validator->messages()->first());
        }
       if($request->get('old_pass'))
       {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'old_pass' => 'required',
            'new_pass' => 'required',
            'conf_pass' => 'required',
            //'email' => 'required|email|unique:users,email,'.$id,
        ]);
        
        if ($validator->fails()) 
        { 
            return redirect()->back()->with('err_message',$validator->messages()->first());
        }

        if ((Hash::check($request->get('old_pass'), Auth::User()->password)))
        {
            if(strcmp($request->get('old_pass'), $request->get('new_pass')) != 0)
                {
                    if(strcmp($request->get('new_pass'),$request->get('conf_pass'))==0)
                       {
                       }
                       else{
                         return redirect('login/profile')->with('err_message','Newpassword confpassword does not match!');
                         }
                 }
            else
               {
                
                return redirect('login/profile')->with('err_message','Current password and new password are same!');
                }
       }
         else
            {
                return redirect('login/profile')->with('err_message','Incorrect old password!');
                 
            }

        $user = User::find(Auth::user()->id);
        $user->password=bcrypt($request->get('new_pass'));
        $user->name = $request->name;
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->save();

        return redirect('login/profile')->with('success', 'Profile successfully updated');
        }
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->save();

        return redirect('login/profile')->with('success', 'Profile successfully updated');
        
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $module_name=$this->permission(Auth::user()->id);
        $user = User::find($id);
        $role_data=Role::whereNotIn('role',['counsellor'])->get();
        $module_data=Module::get();
        //$userRole = $user->roles->pluck('name','name')->all();
        return view('admin.users.edit',compact('user','role_data','module_data','module_name'));
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
        $user->timezone = $request->timezone;
        $user->save();

        return redirect('login/users')->with('success', 'Successfully updated');
        
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id='')
    {
         $booking_id=Booking::where('user_id', $request->id)->first();
         $availability_id=Availability::where('user_id', $request->id)->first();
         $listing_id=Listing::where('user_id', $request->id)->first();
         $pacakage_id=Package::where('user_id', $request->id)->first();
         $payment_id=Payment::where('user_id', $request->id)->first();
         $stripe_connect=StripeConnect::where('user_id', $request->id)->first();
         $user_ticket=UserTickets::where('user_id', $request->id)->first();
         if($booking_id == "" && $availability_id == "" && $listing_id== "" && $pacakage_id == "" && $payment_id == "" && $stripe_connect =="" && $user_ticket=="")
         {
            User::where('id', $request->id)->delete();
           return redirect('login/users')->with('success','User deleted successfully');
         }
         else
         {
             return redirect('login/users')->with('err_message','Something went wrong!');
         }
    }
    public function download(Request $request)
    {
        $status=$request->status;
        $email=$request->email;
        $users=User::where('role_id','=',3)->get();
        $writer = WriterEntityFactory::createXLSXWriter(Type::XLSX);
        $writer->openToBrowser('User-Report'.date('Y-m-d:hh:mm:ss').'.xlsx');
        $column = [
                        WriterEntityFactory::createCell('User Name'),
                        WriterEntityFactory::createCell('Email'),
                        WriterEntityFactory::createCell('Status'),
                    ];
        $singleRow = WriterEntityFactory::createRow($column);
        $writer->addRow($singleRow);
        if($email != "" && $status != "")
            {
              $users=User::where('role_id',"3")->where('account_enabled',"1")->where('name', 'like', '%' . $email . '%')
             ->orwhere('email', 'like', '%' . $email . '%')->get();
              foreach ($users as $key => $user) 
                    {
                       
                       $cells = [
                            WriterEntityFactory::createCell($user['name']),
                            WriterEntityFactory::createCell($user['email']),
                            WriterEntityFactory::createCell($user['account_enabled']),
                           
                        ];
                        $singleRow = WriterEntityFactory::createRow($cells);
                        $writer->addRow($singleRow); 
                   }
            } 
         elseif($email != "")
            {
              $users=User::where('role_id',"3")->where('name', 'like', '%' . $email . '%')
             ->orwhere('email', 'like', '%' . $email . '%')->get();
              foreach ($users as $key => $user) 
                    {
                       
                       $cells = [
                            WriterEntityFactory::createCell($user['name']),
                            WriterEntityFactory::createCell($user['email']),
                            WriterEntityFactory::createCell($user['account_enabled']),
                           
                        ];
                        $singleRow = WriterEntityFactory::createRow($cells);
                        $writer->addRow($singleRow); 
                   }
            } 
            elseif($status == "1")
            {
              $users=User::where('role_id',"3")->where('account_enabled',"1")->get();
              foreach ($users as $key => $user) 
                    {
                       
                       $cells = [
                            WriterEntityFactory::createCell($user['name']),
                            WriterEntityFactory::createCell($user['email']),
                            WriterEntityFactory::createCell($user['account_enabled']),
                           
                        ];
                        $singleRow = WriterEntityFactory::createRow($cells);
                        $writer->addRow($singleRow); 
                   }
            } 
             elseif ($status == "0")
               {
                 $users=User::where('role_id',"3")->where('account_enabled',"0")->get();
                  foreach ($users as $key => $user) 
                        {
                           
                           $cells = [
                                WriterEntityFactory::createCell($user['name']),
                                WriterEntityFactory::createCell($user['email']),
                                WriterEntityFactory::createCell($user['account_enabled']),
                               
                            ];
                            $singleRow = WriterEntityFactory::createRow($cells);
                            $writer->addRow($singleRow); 
                        }
                }   
                elseif ($status == "3")
               {
                 $users=User::where('role_id',"3")->where('account_enabled',"3")->get();
                  foreach ($users as $key => $user) 
                        {
                           
                           $cells = [
                                WriterEntityFactory::createCell($user['name']),
                                WriterEntityFactory::createCell($user['email']),
                                WriterEntityFactory::createCell($user['account_enabled']),
                               
                            ];
                            $singleRow = WriterEntityFactory::createRow($cells);
                            $writer->addRow($singleRow); 
                       }
                }    
            else
            {
              foreach ($users as $key => $user) 
                    {
                       
                       $cells = [
                            WriterEntityFactory::createCell($user['name']),
                            WriterEntityFactory::createCell($user['email']),
                            WriterEntityFactory::createCell($user['account_enabled']),
                           
                        ];
                        $singleRow = WriterEntityFactory::createRow($cells);
                        $writer->addRow($singleRow); 
                   }  
            }   
                    $writer->close();
                    exit();
    }

}