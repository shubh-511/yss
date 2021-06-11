<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\Booking;
use App\User;
use App\CallLog;
use App\Availability;
use DateTime;
use DateTimeZone;
use Event;
use PDF;
use Carbon\Carbon;
use App\Events\UserRegisterEvent;
use App\Notification;
use App\LeftSession;

class BookingController extends Controller
{
    public $successStatus = 200;
	

    /** 
     * Get bookings 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function bookingList(Request $request) 
    {
        $roleId = Auth::user()->role_id;
        if($roleId == 1)
        {


          if ($request->get('name') != null && $request->get('booking_date') != null && $request->get('status') != null) 
          {
          $bookings = Booking::with('user')->whereHas('counsellor', function ($query) use ($request)
          {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
             })->whereHas('user', function ($query) use ($request)
           {
         $query->where('booking_date', 'LIKE', '%' . $request->get('booking_date') . '%');
        })->whereHas('user', function ($query) use ($request)
           {
         $query->where('status', 'LIKE', '%' . $request->get('status') . '%');
        })->orwhereHas('user', function ($query) use ($request)
           {
             $query->where('name', 'LIKE', '%' . $request->name . '%');
            })->whereHas('user', function ($query) use ($request)
           {
         $query->where('booking_date', 'LIKE', '%' . $request->get('booking_date') . '%');
        })->whereHas('user', function ($query) use ($request)
           {
         $query->where('status', 'LIKE', '%' . $request->get('status') . '%');
        })->orderBy('id','DESC')->paginate(25);
            return view('admin.bookings.index',compact('bookings'));
        }
        if ($request->get('name') != null && $request->get('booking_date') != null) 
          {
            
          $bookings = Booking::with('user')->whereHas('counsellor', function ($query) use ($request)
          {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
             })->whereHas('user', function ($query) use ($request)
           {
         $query->where('booking_date', 'LIKE', '%' . $request->get('booking_date') . '%');
        })->orwhereHas('user', function ($query) use ($request)
           {
             $query->where('name', 'LIKE', '%' . $request->name . '%');
            })->whereHas('user', function ($query) use ($request)
           {
         $query->where('booking_date', 'LIKE', '%' . $request->get('booking_date') . '%');
        })->orderBy('id','DESC')->paginate(25);
            return view('admin.bookings.index',compact('bookings'));
        }
        if ($request->get('name') != null && $request->get('status') != null) 
          {
          $bookings = Booking::with('user')->whereHas('counsellor', function ($query) use ($request)
          {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
             })->whereHas('user', function ($query) use ($request)
           {
         $query->where('status', 'LIKE', '%' . $request->get('status') . '%');
        })->orwhereHas('user', function ($query) use ($request)
           {
             $query->where('name', 'LIKE', '%' . $request->name . '%');
            })->whereHas('user', function ($query) use ($request)
           {
         $query->where('status', 'LIKE', '%' . $request->get('status') . '%');
        })->orderBy('id','DESC')->paginate(25);
            return view('admin.bookings.index',compact('bookings'));
        }
        if ($request->get('name') != null) 
          {
          $bookings = Booking::with('user')->whereHas('counsellor', function ($query) use ($request)
          {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
             })->orwhereHas('user', function ($query) use ($request)
           {
         $query->where('name', 'LIKE', '%' . $request->name . '%');
        })->orderBy('id','DESC')->paginate(25);
            return view('admin.bookings.index',compact('bookings'));
        }
        $bookings = Booking::where(function ($query) use($request) {
        if ($request->get('status') != null) { 
        $query->where('status',$request->get('status'));
        } 
        if ($request->get('booking_date') != null) {
         $query->where('booking_date','like', '%' . date('Y-m-d', strtotime($request->get('booking_date'))). '%');
        }

        if ($request->get('booking_date') != null && $request->get('status') != null)
       {
          $query->where('booking_date', 'like', '%' . $request->get('booking_date') . '%')
          ->where('status',$request->get('status'));
        }
       })->orderBy('id','DESC')->paginate(25);
            return view('admin.bookings.index',compact('bookings'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
        }
        else
        {
          if ($request->get('name') != null) 
          {
          $bookings = Booking::with('user')->whereHas('counsellor', function ($query) use ($request)
          {
            $query->where('name', 'LIKE', '%' . $request->name . '%')->where('counsellor_id', Auth::user()->id);
             })->orwhereHas('user', function ($query) use ($request)
           {
         $query->where('name', 'LIKE', '%' . $request->name . '%')->where('counsellor_id', Auth::user()->id);
        })->orderBy('id','DESC')->paginate(25);
            return view('admin.bookings.index',compact('bookings'));
        }
        $bookings = Booking::where(function ($query) use($request) {
        if ($request->get('status') != null) { 
        $query->where('status',$request->get('status'));
        } 
        if ($request->get('booking_date') != null) {
         $query->where('booking_date','like', '%' . date('Y-m-d', strtotime($request->get('booking_date'))). '%');
        }

        if ($request->get('booking_date') != null && $request->get('status') != null)
       {
          $query->where('booking_date', 'like', '%' . $request->get('booking_date') . '%')
          ->where('status',$request->get('status'));
        }
       })->where('counsellor_id', Auth::user()->id)->orderBy('id','DESC')->paginate(25);
            return view('admin.bookings.index',compact('bookings'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
        }
    	
        
    }

    public function active(Request $request)
    {
       $data=$request['action'];
       $id=$request['id'];
       if($data=="confirm")
       {
        $user_data=Booking::whereIn('id', $id)
       ->update(['status' => '1']);
       return response()->json(array('message' => 'success'));
       }
       else if($data=="failed")
       {
        $user_data=Booking::whereIn('id', $id)
       ->update(['status' => '0']);
       return response()->json(array('message' => 'success'));
      }
        else if($data=="cancel")
       {
        $user_data=Booking::whereIn('id', $id)
       ->update(['status' => '4']);
       return response()->json(array('message' => 'success'));
       }
       else
       {
         $user_data=\App\Booking::whereIn('id',$id)->delete();
          return response()->json(array('message' => 'success'));
       
       }
    }

    /**
     * Display a listing of the calls for booking Id.
     *
     */

    public function callHistory($bookingId='')
    {
        $callLogs = CallLog::with('init_by','pick_by','cut_by')->where('booking_id',$bookingId)->get();
        if(count($callLogs) > 0)
        {
            return view('admin.bookings.call_history',compact('callLogs','bookingId'));    
        }
        else
        {
            return redirect()->back()->with('success','No call history found for this booking');
        }
        
    }

    /**
     * Display a listing of the counsellors.
     *
     * @return \Illuminate\Http\Response
     */
    public function createBooking()
    {
        $users = User::where('role_id','=',3)->orderBy('id','DESC')->get();
        $counsellors = User::where('role_id','=',2)->orderBy('id','DESC')->get();
        return view('admin.bookings.create_booking',compact('users','counsellors'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCounsellorPackage()
    {
        $counsellorId = $_GET['counsellorId'];
        if(!empty($counsellorId))
        {
            $packages = Package::where('user_id', $counsellorId)->orderBy('id','DESC')->get();
        
            if(count($packages) > 0)
            {
                echo "<div class='col-md-6'>
                  <h4 style='margin-bottom: 25px;margin-top: 25px;' class='control-label nopadding' >2. Select package and proceed to book:</h4>
                  
                </div>
                <div class='col-md-12'>";
                  
                
                foreach($packages as $package)
                {
                      echo "<div class='col-lg-4 col-md-12 mb-4'>
                        
                        <div class='card' onClick=getAppointment($package->id,$package->no_of_slots)>

                          <div class=card-body'>

                            <p class='text-uppercase small mb-2'><strong>".$package->package_name."</strong></p>
                            <h5 class='font-weight-bold mb-0'>
                              <strong>Amount: </strong>".$package->amount."
                              <small class='text-success ml-2'>
                                <i class='fas fa-arrow-up fa-sm pr-1'></i></small>
                            </h5>
                            <h5 class='font-weight-bold mb-0'>
                              <strong>Session: </strong>".$package->no_of_slots."
                              
                            </h5>
                             <h5 class='font-weight-bold mb-0'>
                              <strong>Seleted: </strong><span id='packageToBook'></span>
                              
                            </h5>
                           
                            <p class='small mb-2'><strong>Duration: </strong>".$package->session_hours.":".$package->session_minutes." Hours";
                            echo "</p>

                            <hr>

                          </div>

                        </div>
                        
                       
                      </div>";
                }
                    echo "</div>";
            }
            else
            {
                echo "<p style='margin: 15px;'>No package found for this counsellor!</p>";
            }   
        }
        else
        {
            echo "Please select a counsellor from the droplist first!";
        }
        
        
    }


    public function makeCustomBooking(Request $request)
    {
        $counsellor = User::where('id', $request->counsellor_id)->first();
        $user = User::where('id', $request->user_id)->first();
        $packageDetail = Package::with('user')->where('id', $request->package_id)->first();
        $selectedSlots = join(",", $request->my_slots);
        $myslots = explode(",", $selectedSlots);
        foreach($myslots as $slot) 
        {
            $customBooking = new Booking;
            $customBooking->counsellor_id = $request->counsellor_id;
            $customBooking->user_id = $request->user_id;
            //$customBooking->created_by = '2';
            $customBooking->payment_id = 0;
            $customBooking->package_id = $request->package_id;
            

            if($counsellor->timezone == $user->timezone)
            {
              $customBooking->slot = $slot;
              $customBooking->booking_date = $request->date;

              $customBooking->counsellor_timezone_slot = $slot;
              $customBooking->counsellor_booking_date = $request->date;
              
            }
            else
            {
              
              date_default_timezone_set($user->timezone);
              $slotFromTimeCounsellor = strtotime( $request->date . ' '.$slot );
              $cc = (new DateTime('@' . $slotFromTimeCounsellor))->setTimezone(new DateTimeZone($user->timezone));
              date_default_timezone_set($counsellor->timezone);
              $ts = $cc->getTimestamp();
              $ucc = (new DateTime('@' . $ts))->setTimezone(new DateTimeZone($counsellor->timezone));
              $counsellorTime = $ucc->format('g:i A');
              $convertedDateCounsellor = $ucc->format('Y-m-d');

             
                 
              $customBooking->slot = $counsellorTime; //$convertedSlotCounsellor;
              $customBooking->booking_date = $convertedDateCounsellor;

              $customBooking->counsellor_timezone_slot = $slot;
              $customBooking->counsellor_booking_date = $request->date;
              
            }
             $userBody = "Your booking for ".$packageDetail->package_name." Package has been successfull.";
            $customBooking->status = '1';
            $customBooking->save();
            $newNotif = new Notification;
            $newNotif->receiver = $user->id;
            $newNotif->title = "Booking created";
            $newNotif->body = $userBody;
            $time=Carbon::now($user->timezone)->toDateTimeString();
            $newNotif->created_at=$time;
            $newNotif->save();
            $CounsellorBody = $user->name." successfully booked your ".$packageDetail->package_name." Package.";
            $newNotif = new Notification;
            $newNotif->receiver = $counsellor->id;
            $newNotif->title = "Booking created";
            $newNotif->body = $CounsellorBody;
            $time=Carbon::now($counsellor->timezone)->toDateTimeString();
            $newNotif->created_at=$time;
            $newNotif->save();

            }
               $left_session_val=$packageDetail->no_of_slots-count($myslots);
              if($left_session_val > 0)
              {
               $left_session=new LeftSession();
               $left_session->user_id=$user->id;
               $left_session->package_id=$packageDetail->id;
               $left_session->payment_id=1;
               $left_session->left_sessions=$left_session_val;
               $left_session->save();
              }
             
        echo 1;
        
    }

    public function downloadreport($bookingId)
    {
      try
      {
        $user = Auth::user();
        $booking = Booking::with('payment_detail','counsellor','user','package')->where('id', $bookingId)->first();
        if(!empty($booking))
      {
        $userTimezone = $booking->user->timezone;
        $currentUserTime = Carbon::now($userTimezone)->format('Y-m-d h:i A');
        $logs = CallLog::with('initiated_by','picked_by','cutted_by','booking')->where('booking_id', $bookingId)->get();
        $data["currentUserTime"] = $currentUserTime;
        $data["logs"] = $logs;
        $data["bookingData"] = $booking;
            $pdf = PDF::loadView('emails.report', $data);
            return $pdf->download('report.pdf');
        }
      }
        catch(\Exception $e)
        {
        return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
      } 
    }

}
