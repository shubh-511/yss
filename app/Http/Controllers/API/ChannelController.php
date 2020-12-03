<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\AvailaibleHours;
use App\Booking;
use App\Availability;
use App\VideoChannel;
use Event;
use Carbon\Carbon;
use App\Events\UserRegisterEvent;

class ChannelController extends Controller
{
    public $successStatus = 200;
	

    /** 
     * Create Channel api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function createChannel(Request $request) 
    {
    	try
        {
    		$validator = Validator::make($request->all(), [ 
	            'from_id' => 'required',  
	            'to_id' => 'required', 
	            'channel_id' => 'required|max:190', 
                'timing' => 'required', 
                'uid' => 'required|max:190',
                'status' => 'required',
	        ]);

			if ($validator->fails()) 
            { 
	            return response()->json(['errors'=>$validator->errors()], $this->successStatus);     
			}

            $user = Auth::user()->id;

            $channelData = new VideoChannel;
            $channelData->from_id = $request->from_id;
            $channelData->to_id = $request->to_id;
            $channelData->channel_id = $request->channel_id;
            $channelData->timing = $request->timing;
            $channelData->uid = $request->uid;
            $channelData->status = $request->status;
            $channelData->save();

	        return response()->json(['success' => true,
	            					 'data' => $channelData,
	            					], $this->successStatus); 

    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }

    
     
     
}
