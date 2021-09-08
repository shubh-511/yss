<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\GeneralSetting;
use DB;
use Auth;
use Hash;
use Validator;
use File;
use App\Traits\CheckPermission;

class SettingController extends Controller
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
        $commission = GeneralSetting::where('id','=',1)->first();
        return view('admin.settings.index',compact('commission','module_name'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCommission(Request $request)
    { 
        $validator = Validator::make($request->all(), [ 
            'admin_commission' => 'required|numeric',
            'counsellor_commission' => 'required|numeric',
            'stripe_secret' => 'required',
            'stripe_public' => 'required',
            'input_img' => 'mimes:jpeg,jpg,png',
            'pagination_value' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) 
        { 
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $commission = GeneralSetting::where('id', 1)->first();
        $commission->admin_commission = $request->admin_commission;
        $commission->counsellor_commission = $request->counsellor_commission;
        $commission->stripe_secret = $request->stripe_secret;
        $commission->stripe_public = $request->stripe_public;
        $commission->email = $request->email;
        $commission->fb_url = $request->fb_url;
        $commission->google_url = $request->google_url;
        $commission->twitter_url = $request->twitter_url;
        $commission->pagination_value = $request->pagination_value;
         if ($request->hasFile('input_img')) {
        $image = $request->file('input_img');
        $name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/logo/');
        if($commission->logo_url != ''  && $commission->logo_url != null){
            if (file_exists($destinationPath.$name)){
               $file_old = $destinationPath.$commission->logo_url;
               unlink($file_old);
           }
          }
        $image->move($destinationPath, $name);
        $commission->logo_url=$name;
        }
        $commission->save();

        return redirect('login/settings')->with('success', 'Successfully updated');
        
    }


}