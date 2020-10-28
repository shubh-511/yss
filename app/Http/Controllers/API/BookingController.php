<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\Booking;
use App\Availability;
use Event;
use Carbon\Carbon;
use App\Events\UserRegisterEvent;

class BookingController extends Controller
{
    public $successStatus = 200;
	

    /** 
     * Make booking api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function makeBooking(Request $request) 
    {
    	try
        {
    		$validator = Validator::make($request->all(), [ 
	            'counsellor_id' => 'required',  
                'package_id' => 'required', 
	            'slot' => 'required', 
	            'booking_date' => 'required', 
	        ]);

			if ($validator->fails()) 
            { 
	            return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
			}
            $user = Auth::user()->id;

			$input = $request->all(); 
            
	        $booking = new Booking; 
            $booking->user_id = $user;
            $booking->counsellor_id = $request->counsellor_id;
            $booking->slot = $request->slot;
            $booking->booking_date = $request->booking_date;
            $booking->package_id = $request->package_id;
            $booking->save();


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
     * Get Booking api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getBooking(Request $request) 
    {
        try
        {
            $user = Auth::user()->id;
            
            $allBookings = Booking::where('counsellor_id', $user)->get(); 
            if(count($allBookings) > 0)
            {
                $pastBookings = Booking::where('counsellor_id', $user)->where('booking_date', '<' Carbon::today())->get();
                $todaysBooking = Booking::where('counsellor_id', $user)->where('booking_date', Carbon::today())->get();
                $upcomingBooking = Booking::where('counsellor_id', $user)->where('booking_date', '>', Carbon::today())->get();

                return response()->json(['success' => true,
                                     'past' => $pastBookings,
                                     'todays' => $todaysBooking,
                                     'upcoming' => $upcomingBooking,
                                    ], $this->successStatus);
            }
            else
            {
                return response()->json(['success' => false,
                                     'message' => 'No bookings found',
                                    ], $this->successStatus);
            }
             

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }

}
