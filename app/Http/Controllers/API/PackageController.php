<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use Event;
use App\Events\UserRegisterEvent;

class PackageController extends Controller
{
    public $successStatus = 200;
	

    /** 
     * Create Package api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function createPackage(Request $request) 
    {
    	try
        {
    		$validator = Validator::make($request->all(), [ 
                'user_id' => 'required', 
	            'package_name' => 'required',  
	            'package_description' => 'required', 
	            'session_minutes' => 'required', 
                'session_hours' => 'required', 
                'amount' => 'required',
	        ]);

			if ($validator->fails()) 
            { 
	            return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
			}

			$input = $request->all(); 
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


    /** 
     * Edit Package api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function editPackage(Request $request) 
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                'package_id' => 'required', 
                'package_name' => 'required',  
                'package_description' => 'required', 
                'session_minutes' => 'required', 
                'session_hours' => 'required', 
                'amount' => 'required',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
            }

            
            $package = Package::where('id', $request->package_id)->first();
            $package->package_name = $request->package_name; 
            $package->package_description = $request->package_description; 
            $package->session_minutes = $request->session_minutes; 
            $package->session_hours = $request->session_hours; 
            $package->amount = $request->amount; 
            $package->save();

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
     * Delete Package api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function deletePackage(Request $request) 
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                'user_id' => 'required', 
                'package_id' => 'required', 
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
            }

            
            $package = Package::where(['id'=> $request->package_id, 'user_id'=> $request->user_id])->delete();

            if($package)
            {
                return response()->json(['success' => true,
                                     'message' => 'Package deleted successfully',
                                    ], $this->successStatus);
            }
            else
            {
                return response()->json(['success' => false,
                                     'message' => 'This package does not exist',
                                    ], $this->successStatus);
            }

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }
     
     
}
