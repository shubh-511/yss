<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\InsuranceLead; 

class InsuranceLeadController extends Controller
{
	public $successStatus = 200;
    Public function insurance(Request $request)
    {
    	try
    	{
    	    $validator = Validator::make($request->all(), [ 
	            'first_name' => 'required|max:190',
	            'last_name' => 'required|max:190',  
	            'email' => 'required|max:190|email|unique:insurance_leads',  
	            'phone' => 'required|numeric',
	            'insurance_provider'=>'required|max:190', 
	            'insurance_no'=>'required|max:190', 
	        ]);

			if ($validator->fails())
			   { 
	              return response()->json(['errors'=>$validator->errors()], $this->successStatus);
			    }
			$input = $request->all();
			$input['first_name'] = $input['first_name'];
			$input['last_name'] = $input['last_name']; 
            $input['email'] = $input['email'];
			$input['phone'] = $input['phone'];
			$input['insurance_no'] = $input['insurance_no'];
			$input['insurance_provider'] = $input['insurance_provider']; 
			$insurance = InsuranceLead::create($input); 
			return response()->json(['success' => true,
	            					 'insurance' => $insurance,
	            					], $this->successStatus); 
		}
		catch(\Exception $e){
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
    }
}
