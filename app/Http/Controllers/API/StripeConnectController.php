<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\StripeConnect;
use Illuminate\Support\Facades\Auth; 
use App\User; 
use Validator;
use Stripe;
use Event;
use App\Traits\ProfileStatusTrait;

class StripeConnectController extends Controller
{
	use ProfileStatusTrait;
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
				Stripe\Stripe::setApiKey('sk_test_51IVk6mInEL6a47XwZYFPim5hOAN95WkN46LgAJHAMzu6FnnH1xPZ0C9HoK4xXRwtZiBWUrbX5OpKThxiO0HpmZsi001GW383pW');
    	
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


				$userUpdate = User::where('id', $user)->update(['is_acct_connected' => '1']);
				$profilePercentage = $this->profileStatus(Auth::user()->id);
                $userData = User::where('id', Auth::user()->id)->first();

				return response()->json(['success' => true,
	            					 'message' => 'Stripe account has been linked',
	            					 'user'	=>	$userData
	            					], $this->successStatus);
			}
			else
			{
				/*return response()->json(['success' => false,
	            					 'message' => 'Your account is already linked with us',
	            					], $this->successStatus);*/

				return response()->json(['success'=>false,'errors' =>['exception' => ['Your account is already linked with us']]], $this->successStatus);
			}
			

    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }

    /** 
     * Stripe Remove Account API
     *  
     * @return \Illuminate\Http\Response 
     */ 
    public function removeUserAccount(Request $request) 
    {
    	try
    	{
			$user = Auth::user()->id;

			$checkExist = StripeConnect::where('user_id', $user)->first();
			
			if(!empty($checkExist))
			{
				$stripe = new \Stripe\StripeClient(
				  'sk_test_51IVk6mInEL6a47XwZYFPim5hOAN95WkN46LgAJHAMzu6FnnH1xPZ0C9HoK4xXRwtZiBWUrbX5OpKThxiO0HpmZsi001GW383pW'
				);

				$isDeleted = $stripe->accounts->delete(
				  'acct_1GXoJnDONzaKgGcK',
				  []
				);
				
				if($isDeleted->deleted == true)
				{
					$checkIfDeleted = StripeConnect::where('user_id', $user)->delete();
					if($checkIfDeleted == 1)
					{
						return response()->json(['success' => true,
	            					 'message' => 'Stripe account has been removed successfully'
	            					], $this->successStatus);
					}
					else
					{
						return response()->json(['success'=>false,'errors' =>['exception' => ['The account does not exist']]], $this->successStatus);
					}
					
				}
				
			}
			else
			{
				return response()->json(['success'=>false,'errors' =>['exception' => ['Your account is not linked with us']]], $this->successStatus);
			}
			
    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }

}