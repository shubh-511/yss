<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use Event;
use App\Events\UserRegisterEvent;
use App\Events\ForgotPasswordEvent;

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
            $checkUserRoles = User::where('email', $request->getUser())->first();
            if(!empty($checkUserRoles))
            {
                if($checkUserRoles->role_id == 3)
                {
                    if (Auth::attempt(array('email' => $request->getUser(), 'password' => $request->getPassword()), true)){
                        $user = Auth::user(); 
                        Auth::user()->roles;
                        $token =  $user->createToken('yss')->accessToken; 

        	            return response()->json(['success' => true,
        	            						 'user' => $user,
        	            						 'token'=> $token
        	            						], $this->successStatus); 
        	        } 
        	        else{ 
        	            return response()->json(['error'=> ['login_failed' => ['Username or Password is not correct']]], 401); 
        	        } 
                } 
                else
                {
                    $url = env('WORDPRESS_LOGIN_URL')."?email=".$request->getUser()."&password=".$request->getPassword();
                    //echo $url; die; 

                    $cURL = $this->url_get_contents($url); 

                    //echo $cURL['status']; die;

                    //$json = file_get_contents($url);
                    //$cURL = json_decode($json, true);

                    if($cURL['status'] == true) 
                    {
                        if(Auth::loginUsingId($checkUserRoles->id))
                        {
                            $user = Auth::user(); 
                            Auth::user()->roles;
                            $token =  $user->createToken('yss')->accessToken; 

                            return response()->json(['success' => true,
                                                     'user' => $user,
                                                     'token'=> $token
                                                    ], $this->successStatus);
                        }
                    }
                    else
                    {
                        return response()->json(['error'=> ['login_failed' => ['Username or Password is not correct']]], 401); 
                    }

                    
                }
            }  
            else
            {
                return response()->json(['error'=> ['login_failed' => ['Username or Password is not correct']]], 401); 
            }
    	}catch(\Exception $e){
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus);
    	}
    }

    public function url_get_contents($url) 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $data = curl_exec($ch);
        $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($retcode == 200) {
            //return $data;
            $responseData = json_decode($data, TRUE);
            return $responseData;
        } else {
            return null;
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
	            'name' => 'required',  
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
     * Forgot password api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function forgotPassword(Request $request) 
    {
        try
        {
            $validator = Validator::make($request->all(), [  
                'email' => 'required|email', 
            ]);

            if ($validator->fails()) { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);            
            }

            $userDetail = User::where('email', $request->email)->first();
            if(!empty($userDetail))
            {
                
                $forgotKey = base64_encode($request->email);

                //$otp = $this->generateOTP();
                //$userDetail->otp = $otp;
                $userDetail->key = $forgotKey;
                $userDetail->save();

                //Send Otp Over Mail
                
                event(new ForgotPasswordEvent($userDetail->id,$forgotKey));

                return response()->json(['success' => true,
                                         'message' => 'Reset password link has been sent on your email',
                                        ], $this->successStatus); 
            }
            else
            {
                return response()->json(['success'=>false,'errors' =>['exception' => ['Invalid email']]], $this->successStatus); 
            }

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }

    

    /** 
     * Verify Forgot Otp api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function verifyForgotPasswordOtp(Request $request) 
    { 
        try
        {
            $validator = Validator::make($request->all(), [  
                'email' => 'required|email', 
                'otp' => 'required',
            ]);

            if ($validator->fails()) { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);            
            }

            $userDetail = User::where('email', $request->email)->where('otp', $request->otp)->first();
            if(!empty($userDetail))
            {
                $userDetail->otp = null;
                $userDetail->save();


                return response()->json(['success' => true,
                                         'message' => 'OTP verified!',
                                        ], $this->successStatus); 
            }
            else
            {
                return response()->json(['success'=>false,'errors' =>['exception' => ['Invalid email or otp']]], $this->successStatus); 
            }

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        }
    }

    /** 
     * Reset Password after Otp verified api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function resetPassword(Request $request) 
    { 
        try
        {
            $validator = Validator::make($request->all(), [  
                'key' => 'required', 
                'password' => 'required', 
                'c_password' => 'required|same:password',
            ]);

            if ($validator->fails()) { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);            
            }

            $userDetail = User::where('email', base64_decode($request->key))->first();
            if(!empty($userDetail))
            {
                $userDetail->key = null;
                $userDetail->password = bcrypt($request->password); 
                $userDetail->save();


                return response()->json(['success' => true,
                                         'message' => 'Your password has been reset',
                                        ], $this->successStatus); 
            }
            else
            {
                return response()->json(['success'=>false,'errors' =>['exception' => ['The token has been expired']]], $this->successStatus); 
            }

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        }
    }

    /** 
     * Update Profile Picture api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function updateProfileImage(Request $request) 
    {
        try{

            $validator = Validator::make($request->all(), [ 
                'image' => 'required|mimes:jpeg,png,jpg|max:2048',  
                
            ]);

            if ($validator->fails()) { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);            
            }

            $fileName = time().'.'.$request->image->extension();  
            
            $request->image->move(public_path('uploads'), $fileName);
            
            
            $user = Auth::user()->id;
            $updateImage = User::where('id', $user)->first();
            $updateImage->avatar_id = "public/uploads/".$fileName;
            $updateImage->save();

            return response()->json(['success' => true,
                                     'user' => $updateImage,
                                    ], $this->successStatus); 

        }catch(\Exception $e){
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }


    /*
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

            $validator = Validator::make($request->all(), [ 
                'name' => 'required',  
                
            ]);

            if ($validator->fails()) { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);            
            }

            $input = $request->all();

            // if(array_key_exists('first_name',$input) ||
            //    array_key_exists('last_name', $input) ||
            //    array_key_exists('middle_name', $input) ||
            //    array_key_exists('location', $input)){

                $user = User::where('id', Auth::user()->id)->update(['name'=>$request->name]);

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
                
            //}

            // return response()->json(['success' => false,
            //                          'errors' => [ 'error' => 'Wrong parameters sent'],
            //                         ], $this->successStatus); 

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

    /** 
     * Reset Password after Otp verified api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function changePassword(Request $request) 
    {  
        try 
        {
            $validator = Validator::make($request->all(), [  
                'old_password' => 'required', 
                'new_password' => 'required', 
                'c_password' => 'required|same:new_password',
            ]);

            if ($validator->fails()) { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);            
            }

            $user = Auth()->user()->id;
            if(!empty($user))
            {
                $user->password = bcrypt($request->new_password); 
                $user->save();


                return response()->json(['success' => true,
                                         'message' => 'Your password has been reset',
                                        ], $this->successStatus); 
            }
            else
            {
                return response()->json(['success'=>false,'errors' =>['exception' => ['Invalid user']]], $this->successStatus); 
            }

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        }
    }
     
}
