<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\AvailaibleHours;
use App\Booking;
use App\User;
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
                'from_id' => 'required', 
                'to_id' => 'required',
                'booking_id'   => 'required',
                'channel_id' => 'required|max:190', 
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
            $checkExist = VideoChannel::where('booking_id', $request->booking_id)->where('from_id', $request->from_id)->where('to_id', $request->to_id)->first();

            //saving video channel data
            if(empty($checkExist))
            {
              $channelData = new VideoChannel; 
              $channelData->booking_id = $request->booking_id;
              $channelData->from_id = $request->from_id;
              $channelData->to_id = $request->to_id;
              //$channelData->channel_id = $this->generateRandomString(20);
              $channelData->channel_id = $request->channel_id;
              $channelData->timing = $request->timing;
              //$channelData->uid = $request->uid;
              $channelData->status = '0';  //waiting
              $channelData->save();              
            }
            else
            {
                VideoChannel::where('booking_id', $request->booking_id)->where('from_id', $request->from_id)->where('to_id', $request->to_id)->update(['status' => '0']);

                $channelData = VideoChannel::where('booking_id', $request->booking_id)->where('from_id', $from_id)->where('to_id', $request->to_id)->first();
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

    /** 
     * Accept/Decline call 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function acceptOrDecline(Request $request) 
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                //'from_id' => 'required',  
                'user_id' => 'required', 
                'booking_id'   => 'required',
                'status'    =>   'required',
                
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);     
            }

            $user = Auth::user()->id;

            //checking existing channel data
            $checkExist = VideoChannel::where('booking_id', $request->booking_id)->where('from_id', $request->user_id)->where('to_id', $user)->first();

            //saving video channel data
            if(!empty($checkExist))
            {
                if($request->status == 1)
                {
                    $checkExist->status = '1';  //Accepted
                    $checkExist->save();  

                    $channelData = VideoChannel::where('booking_id', $request->booking_id)->where('from_id', $request->user_id)->where('to_id', $user)->first();
            
                    return response()->json(['success' => true,
                                     'data' => $channelData,
                                    ], $this->successStatus); 
                }
                elseif($request->status == 2)
                {
                    $checkExist->status = '2';  //Declined
                    $checkExist->save();

                    $channelData = VideoChannel::where('booking_id', $request->booking_id)->where('from_id', $request->user_id)->where('to_id', $user)->first();
            
                    return response()->json(['success' => true,
                                     'data' => $channelData,
                                    ], $this->successStatus);
                }
                else
                {
                    /*return response()->json(['success' => false,
                                     'message' => 'Incorrect status value',
                                    ], $this->successStatus); */

                    return response()->json(['success'=>false,'errors' =>['exception' => ['Incorrect status value']]], $this->successStatus);
                }
                 

            }
            else
            {
                /*return response()->json(['success' => false,
                                     'message' => 'Incorrect input data',
                                    ], $this->successStatus);*/

                return response()->json(['success'=>false,'errors' =>['exception' => ['Incorrect input data']]], $this->successStatus);
            }
            
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }


    /** 
     * Waiting list api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function waitingList(Request $request) 
    {
        try
        {
            /*$validator = Validator::make($request->all(), [ 
                'user_id' => 'required', 
                'booking_id'   => 'required',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);     
            }*/

            $user = Auth::user()->id;
//return $user;
            $waitingList = VideoChannel::with('booking')->where('to_id', $user)->orWhere('from_id', $user)->where('status', '0')->get();
            if(count($waitingList) > 0)
            {
                foreach($waitingList as $key => $list)
                {
                  $fromUserDetail = User::select('id','name','email','avatar_id','role_id')->where('id', $list->from_id)->first();
                  $toUserDetail = User::select('id','name','email','avatar_id','role_id')->where('id', $list->to_id)->first();
                  $waitingList[$key]->from_id = $fromUserDetail;
                  $waitingList[$key]->to_id = $toUserDetail;
                }
                return response()->json(['success' => true,
                                     'data' => $waitingList,
                                    ], $this->successStatus);
            }
            else
            {
                return response()->json(['success' => true,
                                     'data' => [],
                                    ], $this->successStatus);
            }

             

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }

    /** 
     * Remove channel api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function removeChannel(Request $request) 
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                'booking_id' => 'required', 
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);     
            }

            $user = Auth::user();
            if(!empty($user))
            {
              $waitingList = VideoChannel::where('booking_id', $request->booking_id)->first();
              if(!empty($waitingList))
              {
                if($waitingList->delete())
                {
                  return response()->json(['success' => true,
                                       'message' => 'Removed',
                                      ], $this->successStatus);
                }
                else
                {
                  return response()->json(['success'=>false,'errors' =>['exception' => ['Record not exist']]], $this->successStatus);
                }
                  
              }
              else
              {
                  return response()->json(['success'=>false,'errors' =>['exception' => ['Record not exist']]], $this->successStatus);
              }
            }
            else
            {
              return response()->json(['success'=>false,'errors' =>['exception' => ['You are not authorized']]], $this->successStatus);
            }
             
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
