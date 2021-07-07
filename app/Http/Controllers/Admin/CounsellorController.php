<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Booking;
use App\Package;
use App\Listing;
use App\ListingCategory;
use App\ListingRegion;
use App\ListingLabel;
use App\ListingGallery;
use DB;
use Auth;
use Hash;
use Event;
use Validator;
use File;
use App\multilabel;
use App\Notification;
use Carbon\Carbon;
use App\Events\CounsellorRegisterEvent;
use App\GeneralSetting;
use App\Traits\CheckPermission;



class CounsellorController extends Controller
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
      })->where('role_id','=',2)->orderBy('id','DESC')->paginate($general_setting->pagination_value);
        return view('admin.counsellor.index',compact('users','module_name'))
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
        $list_category=ListingCategory::where('status', '1')->get();
        $list_region=ListingRegion::where('status', '1')->get();
        $list_label=ListingLabel::where('status', '1')->get();
        return view('admin.counsellor.add',compact('list_category','list_region','list_label','module_name'));
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
            'listing_name' => 'required|max:190',
            'location' => 'required|max:190',
            'description' => 'required',
            'listing_category' => 'required',
            'listing_region' => 'required',
            'listing_category' => 'required|not_in:0',
            'listing_region' => 'required',
            'cover_img' => 'mimes:jpeg,jpg,png',
            'phone'=>'nullable|numeric|digits:10',
            'website'=>'nullable|url',
            'video_url'=>'nullable|url',
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
            $counsellor->counsellor_type = $request->counsellor_type;

            if(!empty($request->avatar_id))
            {
                $avtarImage = $this->genImage($request->avatar_id);
                $counsellor->avatar_id = $avtarImage;
            }

            if(!empty($request->cover_img))
            {
                $coverImage = $this->genImage($request->cover_img);

                $counsellor->cover_id = $coverImage;
            }

            $counsellor->save();
            $listingData = new Listing;
            $listingData->user_id = $counsellor->id;
            $listingData->listing_name = $request->listing_name;
            $listingData->location = $request->location;
            $listingData->contact_email_or_url = $request->contact_email_or_url;
            $listingData->description = $request->description;
            $listingData->listing_category = $request->listing_category;
            $listingData->listing_region = $request->listing_region;
            $listingData->lattitude = $request->latitude;
            $listingData->longitude = $request->longitude;
            $listingData->website = $request->website;
            $listingData->phone = $request->phone;
            $listingData->video_url = $request->video_url;
             if(!empty($request->cover_img))
            {
                $listingData->cover_img = $counsellor->cover_id;
            }
            $listingData->save();
            foreach($request->listing_label as $label_id) {
            multilabel::create([
            'listing_id' => $listingData->id,
            'label_id' => $label_id
                ]);
                }
            
             if($request->gallery_images)
            {

                foreach($request->gallery_images as $galleryImages)
                {
                    $galleryimg = new ListingGallery;
                    $galleryimg->listing_id = $listingData->id;
                    $galleryimg->gallery_img = $this->genImage($galleryImages);
                    $galleryimg->save();
                }
            }
            event(new CounsellorRegisterEvent($counsellor->id, $request->password));

            return redirect('login/counsellors')->with('success','Counsellor added successfully');
        
        }
         catch(\Exception $e)
        {
             return redirect()->back()->with('err_message','Something went wrong!');
     }
    }


    public function genImage($img)
    {
        /*$name = uniqid().'.'.$img->getClientOriginalExtension();
        $destinationPath = 'uploads/';
        $file='public/uploads/'.$name;
        $img->move($destinationPath, $name);
        return $file;*/

        $fileName = uniqid().'.'.$img->extension(); 
        $img->move(public_path().'/uploads/', $fileName);
        return "public/uploads/".$fileName;
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
        return view('admin.counsellor.show',compact('user','module_name'));
    }
    public function revenue(Request $request,$id)
    {
        $module_name=$this->permission(Auth::user()->id);
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
                return view('admin.counsellor.blankrevenue',compact('module_name'));
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
             return view('admin.counsellor.revenue',compact('users_data','users_mon_data','module_name'));
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
                return view('admin.counsellor.blankrevenue',compact('module_name'));
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
             return view('admin.counsellor.revenue',compact('users_data','users_mon_data','module_name'));
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
           if($array == array())
             {
                return view('admin.counsellor.blankrevenue',compact('module_name'));
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
             return view('admin.counsellor.revenue',compact('users_data','users_mon_data','module_name'));
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
        $module_name=$this->permission(Auth::user()->id);
        $user = User::find($id);
        $userRole = $user->roles->pluck('name','name')->all();
        return view('admin.counsellor.edit',compact('user','userRole','module_name'));
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
        $user->counsellor_type = $request->counsellor_type;
        $user->timezone = $request->timezone;
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
    public function listedit($id)
    {
        $module_name=$this->permission(Auth::user()->id);
        $list_category=ListingCategory::where('status', '1')->get();
        $list_region=ListingRegion::where('status', '1')->get();
        $list_label=ListingLabel::where('status', '1')->get();
        $list_data=Listing::where('id',$id)->first();
        $gallery_data=ListingGallery::where('listing_id',$list_data->id)->get();
        $multilabel=multilabel::where('listing_id',$list_data->id)->get();
       return view('admin.counsellor.listedit',compact('list_data','list_category','list_region','list_label','gallery_data','multilabel','module_name'));
    }
    public function listupdate(Request $request, $id)
    {
         try
         {
           $validator = Validator::make($request->all(), [ 
            'listing_name' => 'required',
            'location' => 'required',
            'description' => 'required',
            'cover_img' => 'mimes:jpeg,jpg,png',
            'gallery_images.*' => 'mimes:jpeg,jpg,png',
            'phone'=>'nullable|numeric|digits:10',
            'website'=>'nullable|url',
            'video_url'=>'nullable|url',
           ]);
           if ($validator->fails()) 
           { 
              return redirect()->back()->with('err_message',$validator->messages()->first());
            }
            $list_update_data=Listing::where('id',$id)->first();
            $list_update_data->listing_name = $request->listing_name;
            $list_update_data->location = $request->location;
            $list_update_data->longitude = $request->longitude;
            $list_update_data->lattitude = $request->latitude;
            $list_update_data->contact_email_or_url = $request->contact_email_or_url;
            $list_update_data->listing_category = $request->listing_category;
            $list_update_data->listing_region = $request->listing_region;
            $list_update_data->status = $request->status;
            $list_update_data->website = $request->website;
            $list_update_data->phone = $request->phone;
            $list_update_data->video_url = $request->video_url;
            $list_update_data->description = $request->description;
            if(!empty($request->cover_img))
            {
                $coverImage = $this->genImage($request->cover_img);

                $list_update_data->cover_img = $coverImage;
            }
            $list_update_data->save();
             if(!empty($request->gallery_images) && count($request->gallery_images) > 0)
            {
                ListingGallery::where('listing_id', $list_update_data->id)->delete();
                foreach($request->gallery_images as $galleryImages)
                {
                    $galleryimg = new ListingGallery;
                    $galleryimg->listing_id = $list_update_data->id;
                    $galleryimg->gallery_img = $this->genImage($galleryImages);
                    $galleryimg->save();
                }
            }
            $user = User::where('id', $list_update_data->user_id)->first();
            if($request->status=="0")
            {
                $newNotif = new Notification;
                $newNotif->receiver = $list_update_data->user_id;
                $newNotif->title = "Admin disable your listing";
                $newNotif->body = "Listing disable by admin";
                $time=Carbon::now($user->timezone)->toDateTimeString();
                $newNotif->created_at=$time;
                $newNotif->save();
            }
             else if($request->status=="1")
            {
                $newNotif = new Notification;
                $newNotif->receiver = $list_update_data->user_id;
                $newNotif->title = "Admin enable your listing";
                $newNotif->body = "Listing enable by admin";
                $time=Carbon::now($user->timezone)->toDateTimeString();
                $newNotif->created_at=$time;
                $newNotif->save();
            }
            else
            {
                $newNotif = new Notification;
                $newNotif->receiver = $list_update_data->user_id;
                $newNotif->title = "Admin reject your listing";
                $newNotif->body = "Listing reject by admin";
                $time=Carbon::now($user->timezone)->toDateTimeString();
                $newNotif->created_at=$time;
                $newNotif->save();

            }
             

            if(!empty($request->listing_label) && count($request->listing_label) > 0)
            {
                multilabel::where('listing_id', $list_update_data->id)->delete();
                foreach($request->listing_label as $label_id) {
                multilabel::create([
                'listing_id' => $list_update_data->id,
                'label_id' => $label_id
                    ]);
                    }
            }

            return redirect('login/counsellors/list/listedit/'.$id)->with('success','Listing updated successfully');
        }
        catch(\Exception $e)
        {
            return redirect()->back()->with('err_message','Something went wrong!');
        }
    }
}