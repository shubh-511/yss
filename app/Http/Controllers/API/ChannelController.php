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

    /** 
     * Join Session api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function joinSession(Request $request) 
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                //'from_id' => 'required',  
                'counsellor_id' => 'required', 
                'booking_id'   => 'required',
                //'channel_id' => 'required|max:190', 
                'timing' => 'required', 
                //'uid' => 'required|max:190',
                //'status' => 'required',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);     
            }

            $user = Auth::user()->id;

            //checking existing channel data
            $checkExist = VideoChannel::where('booking_id', $request->booking_id)->where('from_id', $user)->where('to_id', $request->counsellor_id)->first();

            //saving video channel data
            if(empty($checkExist))
            {
              $channelData = new VideoChannel; 
              $channelData->booking_id = $request->booking_id;
              $channelData->from_id = $user;
              $channelData->to_id = $request->counsellor_id;
              $channelData->channel_id = $this->generateRandomString(20);
              $channelData->timing = $request->timing;
              //$channelData->uid = $request->uid;
              $channelData->status = '0';  //waiting
              $channelData->save();              
            }
            else
            {
                VideoChannel::where('booking_id', $request->booking_id)->where('from_id', $user)->where('to_id', $request->counsellor_id)->update(['status' => '0']);

                $channelData = VideoChannel::where('booking_id', $request->booking_id)->where('from_id', $user)->where('to_id', $request->counsellor_id)->first();
            }

            return response()->json(['success' => true,
                                     'data' => $channelData,
                                    ], $this->successStatus); 
            

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }


    public function generateRandomString($length) 
    {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) 
      {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    }

    
     
     
}
