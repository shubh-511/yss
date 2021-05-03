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

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $commission = GeneralSetting::where('id','=',1)->first();
        return view('admin.settings.index',compact('commission'));
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
            'stripe_public' => 'required'
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
        $commission->save();

        return redirect('login/settings')->with('success', 'Successfully updated');
        
    }


}