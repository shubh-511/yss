<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\StripeConnect;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Stripe;
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
        	retrun $request->stripe_id;
    		$validator = Validator::make($request->all(), [ 
	            'stripe_id' => 'required',
	        ]);

			if ($validator->fails()) 
            { 
	            return response()->json(['errors'=>$validator->errors()], $this->successStatus);  
			}
			$user = Auth::user()->id;

			$checkExist = StripeConnect::where('user_id', $user)->first();
			
			if(empty($checkExist))
			{
				\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
				$response = \Stripe\OAuth::token([
				  'grant_type' => 'authorization_code',
				  'code' => $request->stripe_id,
				]);

				// Access the connected account id in the response
				$connected_account_id = $response->stripe_user_id;


				$connectAct = new StripeConnect;
				$connectAct->user_id = $user;
				$connectAct->stripe_id = $connected_account_id;
				$connectAct->save();

				return response()->json(['success' => true,
	            					 'message' => 'Stripe account has been linked',
	            					], $this->successStatus);
			}
			else
			{
				return response()->json(['success' => false,
	            					 'message' => 'Your account is already linked',
	            					], $this->successStatus);
			}
			

    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }

}