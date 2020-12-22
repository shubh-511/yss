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
    		$validator = Validator::make($request->all(), [ 
	            'stripe_id' => 'required|max:190',
	        ]);

			if ($validator->fails()) 
            { 
	            return response()->json(['errors'=>$validator->errors()], $this->successStatus);  
			}
			$user = Auth::user()->id;

			$checkExist = StripeConnect::where('user_id', $user)->first();
			
			if(empty($checkExist))
			{
				Stripe\Stripe::setApiKey('sk_test_51HeJy8FLGFzxhmLyc7WD0MjMrLNiXexvbyiYelajGk7OZF8Mvh3y2NUWEIX2XuTfQG2txpl3N38yYSva0qqz7lkj00qOEAhKE9');
    	
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
	            					 'message' => 'Your account is already linked with us',
	            					], $this->successStatus);
			}
			

    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }

}