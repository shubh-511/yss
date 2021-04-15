<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\Booking;
use App\Listing;
use Event;
use Carbon\Carbon;
use App\Events\UserRegisterEvent;

class AdminListingController extends Controller
{
    public $successStatus = 200;
	

    /** 
     * Get all listings
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getListings(Request $request) 
    {
    	$listings = Listing::orderBy('id','DESC')->paginate(25);
        return view('admin.listings.index',compact('listings'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
        
    }

    /** 
     * update listing status
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public static function listingStatus()
    {
        $id=$_GET['id'];
        $status=$_GET['value'];
        $model = Listing::find($id);
        if($model) 
        {
            $model->status = $status;
            $model->save();
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
                $pastBookings = Booking::where('counsellor_id', $user)->where('booking_date', '<', Carbon::today())->get();
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
