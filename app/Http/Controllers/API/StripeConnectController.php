<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\StripeConnect;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Event;

class StripeConnectController extends Controller
{
    public $successStatus = 200;
	

    /** 
     * Stripe Connect API
     *  
     * @return \Illuminate\Http\Response 
     */ 
    public function connectUserAccount(Request $request) 
    {
    	try
        {
    		$validator = Validator::make($request->all(), [ 
	            'stripe_id' => 'required',
	        ]);

			if ($validator->fails()) 
            { 
	            return response()->json(['errors'=>$validator->errors()], $this->successStatus);  
			}

			$user = Auth::user()->id;
			
			$connectAct = new StripeConnect;
			$connectAct->user_id = $user;
			$connectAct->stripe_id = $request->stripe_id;
			$connectAct->save();

	        return response()->json(['success' => true,
	            					 'message' => 'Stripe account has been linked',
	            					], $this->successStatus); 

    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }

}