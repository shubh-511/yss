<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\StripeConnect;
use Illuminate\Support\Facades\Auth; 
use App\User; 
use App\CallLog;
use Validator;
use Stripe;
use Event;
use App\Traits\ProfileStatusTrait;

class CallLogsController extends Controller
{
	use ProfileStatusTrait;
    public $successStatus = 200;
	

    /** 
     * Get Call Logs
     *  
     * @return \Illuminate\Http\Response 
     */ 
    public function getLogs($bookingId) 
    {
    	try
    	{
			$getLog = CallLog::with('init_by:id,name,email,avatar_id')->with('pick_by:id,name,email,avatar_id')->with('cut_by:id,name,email,avatar_id')->with('booking')->where('booking_id', $bookingId)->first();
			
			if(!empty($getLog))
			{
				return response()->json(['success' => true,
	            					 	'data'	=>	$getLog
	            					], $this->successStatus);
			}
			else
			{
				return response()->json(['success'=>false,'errors' =>['exception' => ['Invalid booking Id']]], $this->successStatus);
			}
			

    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }

    /** 
     * Save Call Logs
     *  
     * @return \Illuminate\Http\Response 
     */ 
    public function saveLogs(Request $request) 
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [ 
	            'booking_id' => 'required',
	            'init_by' => 'required',
	            'pick_by' => 'required',
	            'cut_by' => 'required',
	            'status' => 'required',
	            'call_duration' => 'required',
	        ]);

			if ($validator->fails()) 
            { 
	            return response()->json(['errors'=>$validator->errors()], $this->successStatus);  
			}
			$user = Auth::user();

			$log = new CallLog;
			$log->booking_id = $request->booking_id;
			$log->init_by = $request->init_by;
			$log->pick_by = $request->pick_by;
			$log->cut_by = $request->cut_by;
			$log->status = $request->status;
			$log->call_duration = $request->call_duration;
			$log->save();
			
			
				return response()->json(['success' => true,
		            					 'message' => 'The log has been saved',
		            					 'data'	=>	$log
	            					], $this->successStatus);
			

    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }

}