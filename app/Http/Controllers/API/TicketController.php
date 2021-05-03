<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\ListingCategory;
use App\ListingLabel;
use JWTAuth;
use JWT;
use App\User;
use App\UserTickets;
use App\Listing;
use Event;
use Carbon\Carbon;
use App\Events\UserRegisterEvent;

class TicketController extends Controller
{
    public $successStatus = 200;
	

    /** 
     * Raise ticket api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function raiseTicket(Request $request) 
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                'booking_id' => 'required',
                'cancel_reason' => 'required',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);     
            }
            $user = Auth::user()->id;
            
            $userTicket = new UserTickets;
            $userTicket->user_id = $user;
            $userTicket->booking_id = $request->booking_id;
            $userTicket->cancel_reason = $request->cancel_reason;
            $userTicket->save();

            return response()->json(['success' => true,
                                    'message' => 'A ticket has been raised to cancel this appointment',
                                    'data' => $userTicket
                                    ], $this->successStatus);
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        }
    }

    

}
