<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; 
use App\User; 
use App\Notification;
use Validator;
use Event;

class NotificationController extends Controller
{
    public $successStatus = 200;
	

    /** 
     * Get Notification API
     *  
     * @return \Illuminate\Http\Response 
     */ 
    public function getAllNotification(Request $request) 
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [ 
	            'type' => 'required',  
	        ]);

			if ($validator->fails()) 
            { 
	            return response()->json(['errors'=>$validator->errors()], $this->successStatus);     
			}
			$user = Auth::user()->id;

			$checkList = Notification::where('receiver', $user)->get();
			
			if(count($checkList) > 0)
			{
				if($request->type == 2)
				{
					$listUpdate = Notification::where('receiver', $user)->update(['is_read' => '1']);	
				}
				$checkList = Notification::with('sender:id,name,email')->with('receiver:id,name,email')->where('receiver', $user)->orderBy('id','DESC')->paginate(8);

				return response()->json(['success' => true,
	            					 	'data' => $checkList,
	            					], $this->successStatus);
			}
			else
			{
				return response()->json(['success'=>false,'errors' =>['exception' => ['No notification found']]], $this->successStatus);
			}
			

    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }

    /** 
     * Delete Notification API
     *  
     * @return \Illuminate\Http\Response 
     */ 
    public function deleteNotification(Request $request) 
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [ 
	            'notification_id' => 'required',  
	        ]);

			if ($validator->fails()) 
            { 
	            return response()->json(['errors'=>$validator->errors()], $this->successStatus);     
			}
			$user = Auth::user()->id;

			$checkList = Notification::where('receiver', $user)->where('notification_id', $request->notification_id)->first();
			
			if(!empty($checkList))
			{
				$checkDel = $checkList->delete();
				if($checkDel == 1)
				{
					return response()->json(['success' => true,
	            					 	'message' => "Deleted successfully",
	            					], $this->successStatus);
				}
				else
				{
					return response()->json(['success' => false,
	            					 	'message' => "Notification does not exist",
	            					], $this->successStatus);
				}
				
			}
			else
			{
				return response()->json(['success'=>false,'errors' =>['exception' => ['Invalid notification Id']]], $this->successStatus);
			}
			

    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }

}