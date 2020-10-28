<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\Booking;
use App\Availability;
use Event;
use Carbon\Carbon;
use App\Events\UserRegisterEvent;

class BookingController extends Controller
{
    public $successStatus = 200;
	

    /** 
     * Create Package api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function bookingList(Request $request) 
    {
    	try
        {
    		$validator = Validator::make($request->all(), [ 
	            /*'package_name' => 'required',  
	            'package_description' => 'required', 
	            'session_minutes' => 'required', 
                'session_hours' => 'required', 
                'amount' => 'required',*/
	        ]);

			if ($validator->fails()) 
            { 
	            return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
			}
            $user = Auth::user()->id;

			$input = $request->all(); 
            $input['user_id'] = $user;
	        $package = Package::create($input); 

	        return response()->json(['success' => true,
	            					 'package' => $package,
	            					], $this->successStatus); 

    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }

    /** 
     * Get Package api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getPackagesByCounsellorId(Request $request) 
    {
        try
        {
            $user = Auth::user()->id;
            /*$validator = Validator::make($request->all(), [ 
                'user_id' => 'required',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
            }*/
            $allPackages = Package::where('user_id', $user)->get(); 

            return response()->json(['success' => true,
                                     'packages' => $allPackages,
                                    ], $this->successStatus); 

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }

}
