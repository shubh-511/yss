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
    public function getAllNotification() 
    {
    	try
    	{
			$user = Auth::user()->id;

			$checkList = Notification::where('receiver', $user)->get();
			
			if(count($checkList) > 0)
			{
				
				$listUpdate = Notification::where('receiver', $user)->update(['is_read' => '1']);
				$checkList = Notification::with('sender:id,name,email')->with('receiver:id,name,email')->where('receiver', $user)->orderBy('id','DESC')->get();

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

}