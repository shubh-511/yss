<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use Event;
use App\Events\UserRegisterEvent;

class UserController extends Controller
{
    public $successStatus = 200;
	/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){
    	
    	try{

            $input = [];

    		if($request->getUser() || $request->getPassword()){
    			$input['name'] 	= $request->getUser();
    			$input['password'] 	= $request->getPassword();
    		}
    		
    		$validator = Validator::make($input, [ 
	            'name' => 'required', 
	            'password' => 'required'
	        ]);

	        if ($validator->fails()) { 
	            return response()->json(['errors'=>$validator->errors(),'success' => false], $this->successStatus);
			}

            if (Auth::attempt(array('name' => $request->getUser(), 'password' => $request->getPassword()), true)){
                $user = Auth::user(); 
                Auth::user()->roles;
                $token =  $user->createToken('MyApp')->accessToken; 

	            return response()->json(['success' => true,
	            						 'user' => $user,
	            						 'token'=> $token
	            						], $this->successStatus); 
	        } 
	        else{ 
	            return response()->json(['error'=> ['login_failed' => ['Username or Password is not correct']]], 401); 
	        } 
    	}catch(\Exception $e){
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus);
    	}
    }

    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    {
    	try{

    		$validator = Validator::make($request->all(), [ 
	            'name' => 'required|unique:users',  
	            'email' => 'required|email|unique:users', 
	            'password' => 'required', 
	            'c_password' => 'required|same:password', 
	        ]);

			if ($validator->fails()) { 
	            return response()->json(['errors'=>$validator->errors()], $this->successStatus);
			}

			$input = $request->all(); 
			$input['password'] = bcrypt($input['password']); 
            $input['role_id'] = (array_key_exists('role_id',$input)) ? $input['role_id'] : 3;
			$input['otp'] = $this->generateOTP();
            $input['account_enabled'] = 3; // Not verified user
	        $user = User::create($input); 
	        
	        //Send Otp Over Mail or Phone
	        //event(new UserRegisterEvent($user->id));

	        return response()->json(['success' => true,
	            					 'user' => $user,
	            					], $this->successStatus); 

    	}catch(\Exception $e){
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }

    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function generateOTP(){
        $otp = mt_rand(1000,9999);
        return $otp;
    }

	/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this->successStatus); 
    }

    /** 
     * Update User Profile
     * 
     */
    public function updateProfile(Request $request){
        try{

            $input = $request->all();

            if(array_key_exists('first_name',$input) ||
               array_key_exists('last_name', $input) ||
               array_key_exists('middle_name', $input) ||
               array_key_exists('location', $input)){

                $user = User::where('id', Auth::user()->id)->update($input);

                if($user){
                    $user = User::where('id',Auth::user()->id)->with('roles')->first(); 
                    return response()->json(['success' => true,
                                     'user' => $user,
                                    ], $this->successStatus); 
                }else{
                    return response()->json(['success' => false,
                                     'errors' => [ 'error' => 'Wrong parameters sent'],
                                    ], $this->successStatus);
                }
                
            }

            return response()->json(['success' => false,
                                     'errors' => [ 'error' => 'Wrong parameters sent'],
                                    ], $this->successStatus); 

        }catch(\Exception $e){
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
    }
    /** 
     * Import User Api
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function importUser(Request $request) 
    { 
        try{

                $input = (array) $request->all();
            
                $validator = Validator::make($input, [ 
                    'name' => 'required|unique:users',  
                    'email' => 'required|email|unique:users', 
                    'password' => 'required', 
                ]);

                if ($validator->fails()) { 
                    return response()->json(['errors'=>$validator->errors()], $this->successStatus);
                }

                $userData = [];
                $userData['migrated_id'] = $input['user_id'];
                $userData['name'] = $input['name'];    
                $userData['password'] = bcrypt($input['password']); 
                $userData['email'] = $input['email'];    
                $userData['user_nicename'] = $input['user_nicename'];    
                $userData['display_name'] = $input['display_name'];    
                
                if(array_key_exists('roles', $input) 
                    && !empty($input['roles']) 
                    && in_array('administrator', $input['roles'])){

                    $userData['role_id'] = 1;
                }else{

                    $userData['role_id'] = 2;
                }

                $user = User::create($userData); 
                
                return response()->json(['success' => true,
                                         'user' => $user,
                                        ], $this->successStatus);    
                

        }catch(\Exception $e){
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
    }
     
}
