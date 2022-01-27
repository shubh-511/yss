<?php
 
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\Booking;
use App\CartSlot;
use App\StripeConnect;
use App\Availability;
use App\Notification;
use App\User;
use App\VideoChannel;
use App\LeftSession;
use App\Payment;
use App\Refund;
use DB;
use Event;
use Stripe;
use Carbon\Carbon;
use Twilio\Rest\Client;
use App\Events\UserRegisterEvent;
use App\Events\BookingEvent;
use App\Events\BookingCounsellorEvent;
use App\Events\BookLeftSession;
use App\Events\FailedBookingEvent;
use App\Events\CancelBookingEvent;
//use App\Events\CancelBookingByCounsellorEvent;
use DateTime;
use DateTimeZone;
class BookingController extends Controller
{
    public $successStatus = 200;
  

    /** 
     * Add Slots to cart API
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function addSlotsToCart(Request $request) 
    {
        try
        {
            $params = $request->params;
            $rules = $this->validateCartSlots($params);
           
            $validator = Validator::make($params, $rules);


            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);     
            }

            
            $user = Auth::user();
            
            foreach($params['slots'] as $slot) 
            {
              $cartSlots = new CartSlot; 
              $cartSlots->user_id = $user->id;
              $cartSlots->counsellor_id = $params['counsellor_id'];
              $cartSlots->package_id = $params['package_id'];
              $cartSlots->booking_date = $params['booking_date'];
              $cartSlots->slot = $slot;
              $cartSlots->save();
            }

            $addedSlots = CartSlot::where([['user_id', $user->id],['counsellor_id',$params['counsellor_id']],['package_id',$params['package_id']],['booking_date',$params['booking_date']]])->get(); 
            

            return response()->json(['success' => true,
                                      'data' => $addedSlots
                                    ], $this->successStatus);
           
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        }  
        
    }

    /** 
     * Cancel booking
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function cancelBooking(Request $request) 
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
            $user = Auth::user()->id;
            
            $userBooking = Booking::where('id', $request->booking_id)->where('user_id', $user)->where('status', '1')->first();

            if(!empty($userBooking))
            {
                $payment = Payment::where('id', $userBooking->payment_id)->first();
                $package = Package::where('id', $userBooking->package_id)->first();

                if($package->no_of_slots == 1)
                {
                    //refund
                    $stripe = new \Stripe\StripeClient(
                      'sk_test_51IVk6mInEL6a47XwZYFPim5hOAN95WkN46LgAJHAMzu6FnnH1xPZ0C9HoK4xXRwtZiBWUrbX5OpKThxiO0HpmZsi001GW383pW'
                    );

                    $refundPayment = $stripe->refunds->create([
                      'charge' => $payment->charge_id,
                    ]);

                    $refund = new Refund;
                    $refund->user_id = $user;
                    $refund->payment_id = $userBooking->payment_id;
                    $refund->refund_id = $refundPayment->id;
                    $refund->object = $refundPayment->object;
                    $refund->amount = $refundPayment->amount;
                    $refund->balance_transaction = $refundPayment->balance_transaction;
                    $refund->charge_id = $refundPayment->charge;
                    $refund->created = $refundPayment->created;
                    $refund->currency = $refundPayment->currency;
                    $refund->payment_intent = $refundPayment->payment_intent;
                    $refund->reason = $refundPayment->reason;
                    $refund->receipt_number = $refundPayment->receipt_number;
                    $refund->source_transfer_reversal = $refundPayment->source_transfer_reversal;
                    $refund->status = $refundPayment->status;
                    $refund->transfer_reversal = $refundPayment->transfer_reversal;
                    $refund->save();

                    Booking::where('id', $request->booking_id)->where('user_id', $user)->update(['status' => '4']); //refunded
                    Payment::where('id', $userBooking->payment_id)->where('user_id', $user)->update(['status' => 'refunded', 'amount_refunded' => $refundPayment->amount, 'refunded' => 1]);
                }
                else
                {
                    //move to planned session
                    $leftSessions = LeftSession::where('user_id', $user)->where('package_id', $package->id)->first();
                    $leftSessions->left_sessions = $leftSessions->left_sessions + 1;
                    $leftSessions->save();

                    Booking::where('id', $request->booking_id)->where('user_id', $user)->update(['status' => '4']); //cancelled or refunded
                }
                event(new CancelBookingEvent($user));
                return response()->json(['success' => true,
                                         'message' => 'Your booking has been cancelled successfully. Refund if applicable will be issued.'
                                        ], $this->successStatus);
            }
            else
            {
                return response()->json(['success' => false,
                                      'message' => 'Invalid booking id'
                                    ], $this->successStatus);
            } 
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        }  
        
    }

    /** 
     * Get Slots From cart API
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getSlotsFromCart(Request $request) 
    {
        try
        {
            $params = $request->params;
            $rules = $this->validateSlots($params);
           
            $validator = Validator::make($params, $rules);


            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);     
            }

            
            $user = Auth::user();
            
            $cartSlots = CartSlot::where([['user_id', $user->id],['counsellor_id',$params['counsellor_id']],['package_id',$params['package_id']],['booking_date',$params['booking_date']]])->get(); 
            
            if(count($cartSlots) > 0)
            {
              return response()->json(['success' => true,
                                      'data' => $cartSlots
                                    ], $this->successStatus);
            }
            else
            {
              return response()->json(['success' => false,
                                      'message' => 'Currently there is no slots added'
                                    ], $this->successStatus);
            }
            
           
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        }  
        
    }

    /** 
     * Delete Slots from cart API
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function deleteSlotsFromCart(Request $request) 
    {
        try
        {
            $params = $request->params;
            $rules = $this->validateSlots($params);
           
            $validator = Validator::make($params, $rules);


            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);     
            }

            
            $user = Auth::user();
            
            /*foreach ($params['slots'] as $slot) 
            {*/
              $deleteSlots = CartSlot::where([['user_id', $user->id],['counsellor_id',$params['counsellor_id']],['package_id',$params['package_id']],['booking_date',$params['booking_date']]])->whereIn('slot', $params['slots'])->delete();
            //}
            

            $remainingSlots = CartSlot::where([['user_id', $user->id],['counsellor_id',$params['counsellor_id']],['package_id',$params['package_id']],['booking_date',$params['booking_date']]])->get(); 

            if($deleteSlots == 1)
            {
              return response()->json(['success' => true,
                                      'message' => 'deleted successfully',
                                      'data' => $remainingSlots
                                    ], $this->successStatus);
            }
            else
            {
              return response()->json(['success' => false,
                                      'message' => 'Selected Slot does not exist',
                                      'data' => $remainingSlots
                                    ], $this->successStatus);
            }

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        }  
        
    }
     public function bookleftsession(Request $request)
     {
      try{
          $params=json_decode($request->getContent(), true);
          $rules = $this->validateData($params);
          $validator = Validator::make($params, $rules);

          if ($validator->fails()) 
          { 
              return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
          }
          $packageId = Package::with('user')->where('id', $params['package_id'])->first();
          if($packageId)
          {    
          $user = Auth::user();        
          $sessionDetail = LeftSession::where('user_id',$user->id)->where('package_id',$params['package_id'])->where('payment_id',$params['payment_id'])->first();
          $counsellorTimeZone = $packageId->user->timezone;
          $userTimeZone = $user->timezone;
          $offsetCounsellor = Carbon::now($counsellorTimeZone)->offsetMinutes;
          $offsetCounsellor = $offsetCounsellor/2;
          $offsetUser = Carbon::now($userTimeZone)->offsetMinutes;
          $offsetUser = $offsetUser/2;
              $slotArray = [];
              foreach($params['selected_slots'] as $date => $slots)
              {                     
                
                if(count($slots) > 0)
                {
                  foreach($slots as $slot)
                  {
                    $booking = new Booking;
                    $booking->user_id = $user->id;
                    $booking->payment_id = $sessionDetail->payment_id;
                    $booking->counsellor_id = $packageId->user_id;

                    if($counsellorTimeZone == $userTimeZone)
                    {
                      $booking->slot = $slot;
                      $booking->booking_date = $date;

                      $booking->counsellor_timezone_slot = $slot;
                      $booking->counsellor_booking_date = $date;
                    }
                    else
                    {
                      
                      date_default_timezone_set($userTimeZone);
                      $slotFromTimeCounsellor = strtotime( $date . ' '.$slot );
                      $cc = (new DateTime('@' . $slotFromTimeCounsellor))->setTimezone(new DateTimeZone($userTimeZone));
                      date_default_timezone_set($counsellorTimeZone);
                      $ts = $cc->getTimestamp();
                      $ucc = (new DateTime('@' . $ts))->setTimezone(new DateTimeZone($counsellorTimeZone));
                      $counsellorTime = $ucc->format('g:i A');
                      $convertedDateCounsellor = $ucc->format('Y-m-d');

                     
                         
                      $booking->slot = $counsellorTime; //$convertedSlotCounsellor;
                      $booking->booking_date = $convertedDateCounsellor;

                      $booking->counsellor_timezone_slot = $slot;
                      $booking->counsellor_booking_date = $date;
                    }
                    

                    $booking->package_id = $params['package_id'];
                    $booking->status ="1";
                    $booking->save();
                  }
                }
                
                
              }
               $left_session_val=$sessionDetail->left_sessions-$params['no_of_slots'];
              if($left_session_val > 0)
              {
               $left_session=LeftSession::where('id',$sessionDetail->id)->first();
               $left_session->user_id=$user->id;
               $left_session->package_id=$params['package_id'];
               $left_session->payment_id=$sessionDetail->payment_id;
               $left_session->left_sessions=$left_session_val;
               $left_session->save();
                event(new BookLeftSession($user->id,$left_session_val,$params['no_of_slots']));
               }
             else
              {
                $sessionDetail->delete();

              }
                $Left_Sessions=LeftSession::with('package')->with('package.user:id,name,email,avatar_id')->where('user_id',$user->id)->paginate(5);
                $selectedSlots = '';

                $offsetUser = Carbon::now($userTimeZone)->offsetMinutes;
                $offsetCounsellor = Carbon::now($counsellorTimeZone)->offsetMinutes;

                $userCreatedNotification = Carbon::now($userTimeZone);
                $userCreatedNotification = Carbon::parse($userCreatedNotification)->format('Y-m-d H:i:s');

                $counselorCreatedNotification = Carbon::now($counsellorTimeZone);
                $counselorCreatedNotification = Carbon::parse($counselorCreatedNotification)->format('Y-m-d H:i:s');
                $userBody = "Your booking for ".$packageId->package_name." Package has been successful.";
                $newNotif = new Notification;
                $newNotif->receiver = $user->id;
                $newNotif->title = "Booking Successful";
                $newNotif->body = $userBody;
                $newNotif->created_at = $userCreatedNotification;
                $newNotif->updated_at = $userCreatedNotification;
                $newNotif->save();
                $CounsellorBody = $user->name." successfully booked your ".$packageId->package_name." Package.";
                $newNotif = new Notification;
                $newNotif->receiver = $packageId->user_id;
                $newNotif->title = "Booking Successful";
                $newNotif->body = $CounsellorBody;
                $newNotif->created_at = $counselorCreatedNotification;
                $newNotif->updated_at = $counselorCreatedNotification;
                $newNotif->save();
                return response()->json(['success' => true,
                                         'message' => 'Booking Successful!',
                                         'left_session'=>$Left_Sessions,
                                        ], $this->successStatus); 
              }
              else
              {
                return response()->json(['success' => false,
                                         'message' => 'This package dose not exist!',
                                        ], $this->successStatus);
              }
         }
         catch(\Exception $e)
      {
          return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
      } 

     }

    /** 
     * Make booking api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function makeBooking(Request $request) 
    {
      try
      {
          $params = $request->param;
          $rules = $this->validateData($params);
           
          $validator = Validator::make($params, $rules);

          if ($validator->fails()) 
          { 
              return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
          }
          $user = Auth::user();
          $packageDetail = Package::with('user')->where('id', $params['package_id'])->first();
          $sessionMin = $packageDetail->session_minutes;
          $sessionHours = $packageDetail->session_hours;
          if($sessionHours != 0)
          {
              $sessionTime = $sessionHours * 60;
              $sessionTime = $sessionTime + $sessionMin;
          }
          else
          {
              $sessionTime = $sessionMin;
          }
          
          /*$prevBooking = Booking::where('counsellor_id', $params['counsellor_id'])->where('package_id', $params['package_id'])->where('booking_date', $params['booking_date'])->get();

          $prevBookingSlots = $prevBooking->pluck('slot')->toArray();
          $slotsForBooking = $params['slot'];
          $result=array_intersect($prevBookingSlots,$slotsForBooking);
          if(count($result) == 0)
          {*/

            Stripe\Stripe::setApiKey('sk_test_51IVk6mInEL6a47XwZYFPim5hOAN95WkN46LgAJHAMzu6FnnH1xPZ0C9HoK4xXRwtZiBWUrbX5OpKThxiO0HpmZsi001GW383pW');
            
            $stripe = new Stripe\StripeClient('sk_test_51IVk6mInEL6a47XwZYFPim5hOAN95WkN46LgAJHAMzu6FnnH1xPZ0C9HoK4xXRwtZiBWUrbX5OpKThxiO0HpmZsi001GW383pW');


            $customer = \Stripe\Customer::create(array(
                'name' => $user->name,
                'email' => $user->email,
            ));
              
            

            $source = \Stripe\Customer::createSource(
            $customer->id,
            //['source' => $token->id]);
            ['source' => $params['token']]);

              
            //$netAmt = $params['amount'];
            $netAmt = 100;
            $conf = \Stripe\PaymentIntent::create([
              'amount' => str_replace([',', '.'], ['', ''], $packageDetail->amount),
              'description' => $packageDetail->package_name,
              'customer' => $customer->id,
              'currency' => 'GBP',
              'confirmation_method' => 'manual',
              'confirm' => true,
            ]);

             
            $payment = new Payment;
            $payment->user_id = $user->id;

            $payment->charge_id = $conf->charges->data[0]->id;
            $payment->amount = $conf->charges->data[0]->amount;
            $payment->amount_captured = $conf->charges->data[0]->amount_captured;
            $payment->amount_refunded = $conf->charges->data[0]->amount_refunded;
            $payment->application = $conf->charges->data[0]->application;
            $payment->application_fee = $conf->charges->data[0]->application_fee;
            $payment->application_fee_amount = $conf->charges->data[0]->application_fee_amount;
            $payment->balance_transaction = $conf->charges->data[0]->balance_transaction;
            $payment->calculated_statement_descriptor = $conf->charges->data[0]->calculated_statement_descriptor;
            $payment->captured = $conf->charges->data[0]->captured;
            $payment->created = $conf->charges->data[0]->created;
            $payment->currency = $conf->charges->data[0]->currency;
            $payment->customer = $conf->charges->data[0]->customer;

            $payment->description = $conf->charges->data[0]->description;
            $payment->destination = $conf->charges->data[0]->destination;
            $payment->dispute = $conf->charges->data[0]->dispute;
            $payment->disputed = $conf->charges->data[0]->disputed;
            $payment->failure_code = $conf->charges->data[0]->failure_code;
            $payment->failure_message = $conf->charges->data[0]->failure_message;
            $payment->invoice = $conf->charges->data[0]->invoice;
            $payment->livemode = $conf->charges->data[0]->livemode;
            $payment->on_behalf_of = $conf->charges->data[0]->on_behalf_of;
            $payment->paid = $conf->charges->data[0]->paid;
            $payment->payment_intent = $conf->charges->data[0]->payment_intent;
            $payment->payment_method = $conf->charges->data[0]->payment_method;
            $payment->receipt_email = $conf->charges->data[0]->receipt_email;

            $payment->receipt_number = $conf->charges->data[0]->receipt_number;
            $payment->receipt_url = $conf->charges->data[0]->receipt_url;
            $payment->refunded = $conf->charges->data[0]->refunded;
            $payment->review = $conf->charges->data[0]->review;
            $payment->shipping = $conf->charges->data[0]->shipping;
            $payment->source = $conf->charges->data[0]->source->id;
            $payment->source_transfer = $conf->charges->data[0]->source_transfer;
            $payment->statement_descriptor = $conf->charges->data[0]->statement_descriptor;
            $payment->statement_descriptor_suffix = $conf->charges->data[0]->statement_descriptor_suffix;
            $payment->status = $conf->charges->data[0]->status;
            $payment->transfer = $conf->charges->data[0]->transfer;
              
            $payment->save();

            $counsellorTimeZone = $packageDetail->user->timezone;
            $userTimeZone = $user->timezone;

            
            //$bookingDate = $params['booking_date'];
            $offsetCounsellor = Carbon::now($counsellorTimeZone)->offsetMinutes;
            $offsetCounsellor = $offsetCounsellor/2;

            $offsetUser = Carbon::now($userTimeZone)->offsetMinutes;
            $offsetUser = $offsetUser/2;
            
            if($conf->status == 'succeeded')
            {
              $slotArray = [];
              $newArray = [];
              foreach($params['selected_slots'] as $date => $slots)
              {                     

                array_push($newArray, $slots);

                if(count($slots) > 0)
                {
                  foreach($slots as $slot)
                  {
                    $booking = new Booking; 
                    $booking->user_id = $user->id;
                    $booking->payment_id = $payment->id;
                    $booking->counsellor_id = $params['counsellor_id'];
                    $booking->package_time = $sessionTime;

                    $time = date("H:i:s", strtotime($slot));
                    $endTime = strtotime("+".$sessionTime." minutes", strtotime($time));
                    $extendedTime =  date('H:i:s', $endTime);

                    if($counsellorTimeZone == $userTimeZone)
                    {
                      $booking->slot = $slot;
                      $booking->user_start_datetime = $date.' '.$time;
                      $booking->user_end_datetime = $date.' '.$extendedTime;
                      $booking->booking_date = $date;

                      $booking->counsellor_timezone_slot = $slot;
                      $booking->counsellor_booking_date = $date;

                      $booking->counsellor_start_datetime = $date.' '.$time;
                      $booking->counsellor_end_datetime = $date.' '.$extendedTime;
                    }
                    else
                    {
                      
                      date_default_timezone_set($userTimeZone);
                      $slotFromTimeCounsellor = strtotime( $date . ' '.$slot );
                      $cc = (new DateTime('@' . $slotFromTimeCounsellor))->setTimezone(new DateTimeZone($userTimeZone));
                      date_default_timezone_set($counsellorTimeZone);
                      $ts = $cc->getTimestamp();
                      $ucc = (new DateTime('@' . $ts))->setTimezone(new DateTimeZone($counsellorTimeZone));
                      $counsellorTime = $ucc->format('g:i A');
                      $convertedDateCounsellor = $ucc->format('Y-m-d');

                      $time1 = date("H:i:s", strtotime($counsellorTime));
                      $endTime1 = strtotime("+".$sessionTime." minutes", strtotime($time1));
                      $extendedTime1 =  date('H:i:s', $endTime1);

                      $time2 = date("H:i:s", strtotime($counsellorTime));
                      $endTime2 = strtotime("+".$sessionTime." minutes", strtotime($time2));
                      $extendedTime2 =  date('H:i:s', $endTime2);

                      $time = date("H:i:s", strtotime($slot));
                      $endTime = strtotime("+".$sessionTime." minutes", strtotime($time));
                      $extendedTime =  date('H:i:s', $endTime);
                     
                         
                      $booking->slot = $counsellorTime; //$convertedSlotCounsellor;
                      $booking->booking_date = $convertedDateCounsellor;

                      $booking->user_start_datetime = $date.' '.$time1;
                      $booking->user_end_datetime = $date.' '.$extendedTime1;

                      $booking->counsellor_timezone_slot = $slot;
                      $booking->counsellor_booking_date = $date;

                      $booking->counsellor_start_datetime = $date.' '.$time;
                      $booking->counsellor_end_datetime = $date.' '.$extendedTime;
                    }
                    

                    $booking->package_id = $params['package_id'];
                    $booking->notes = $params['notes'];
                    $booking->status = '1';
                    $booking->save();
                  }
                }
                
                
              }

              if($newArray != array())
               {
               $left_session_val=$packageDetail->no_of_slots-count($newArray[0]);
              if($left_session_val > 0)
              {
               $left_session=new LeftSession();
               $left_session->user_id=$user->id;
               $left_session->package_id=$params['package_id'];
               $left_session->payment_id=$payment->id;
               $left_session->left_sessions=$left_session_val;
               $left_session->save();
              }
             } 
               //saving notification 

                /*if(count($params['slot']) > 0)
                {
                  if($user->role_id == 3)
                  {
                    $selectedSlots = "Your selected slots are: ".join(',', $slotArray)." for ".$sessionTime." each";
                  }
                  else
                  {
                    $selectedSlots = "The booked slots are: ".join(',', $slotArray)." for ".$sessionTime." minutes each";
                  }
                }
                else
                {
                  if($user->role_id == 3)
                  {
                    $selectedSlots = "Your selected slot is: ".join(',', $slotArray)." for ".$sessionTime." minutes";
                  }
                  else
                  {
                    $selectedSlots = "The booked slot is: ".join(',', $slotArray)." for ".$sessionTime." minutes";
                  }
                }*/
                $selectedSlots = '';

                $offsetUser = Carbon::now($userTimeZone)->offsetMinutes;
                $offsetCounsellor = Carbon::now($counsellorTimeZone)->offsetMinutes;

                $userCreatedNotification = Carbon::now($userTimeZone);
                $userCreatedNotification = Carbon::parse($userCreatedNotification)->format('Y-m-d H:i:s');

                $counselorCreatedNotification = Carbon::now($counsellorTimeZone);
                $counselorCreatedNotification = Carbon::parse($counselorCreatedNotification)->format('Y-m-d H:i:s');

                //notification to user

                //$userBody = "You have successfully booked ".$packageDetail->package_name." package for amount £".$packageDetail->amount.", ".$selectedSlots;
                $userBody = "Your booking for ".$packageDetail->package_name." Package has been successful.";
                $newNotif = new Notification;
                $newNotif->receiver = $user->id;
                $newNotif->title = "Booking Successful";
                $newNotif->body = $userBody;
                $newNotif->created_at = $userCreatedNotification;
                $newNotif->updated_at = $userCreatedNotification;
                $newNotif->save();

                //notification to counsellor

                //$CounsellorBody = $user->name." successfully booked your ".$packageDetail->package_name." package for amount £".$packageDetail->amount.", ".$selectedSlots;
                $CounsellorBody = $user->name." successfully booked your ".$packageDetail->package_name." Package.";
                $newNotif = new Notification;
                $newNotif->receiver = $packageDetail->user->id;
                $newNotif->title = "Booking Successful";
                $newNotif->body = $CounsellorBody;
                $newNotif->created_at = $counselorCreatedNotification;
                $newNotif->updated_at = $counselorCreatedNotification;
                $newNotif->save();

                //Send Mail
                
                event(new BookingEvent($booking->id, $user->id));

                //Send Mail
                
                event(new BookingCounsellorEvent($booking->id, $params['counsellor_id'], $user->id));

                //send sms for successful booking
                /*if(!empty($user->phone) && !empty($user->country_code))
                $this->sendSMS('+'.$user->country_code, $user->phone);*/
                
                return response()->json(['success' => true,
                                         'message' => 'Your payment has been made successfully!',
                                        ], $this->successStatus); 
           }
           else
           {
              //notification to user
              $body = "Your recent booking was failed due to below reason: ".$conf->status;
              $newNotif = new Notification;
              $newNotif->receiver = $user->id;
              $newNotif->title = "Booking Failed";
              $newNotif->body = $body;
              $newNotif->save();

              //Send Mail
              
              //event(new FailedBookingEvent($booking->id, $user->id));
              //send sms for successful booking

              /*if(!empty($user->phone) && !empty($user->country_code))
              $this->failedBookingSMS('+'.$user->country_code, $user->phone);*/

              return response()->json(['success'=>false,'errors' =>['exception' => [$conf->status]]], $this->successStatus); 
           }
          /*}
          else
          {
            return response()->json(['success'=>false,'errors' =>['exception' => ['Please select different slot as someone has already booked that slot']]], $this->successStatus); 
          }*/

      }
      catch(\Exception $e)
      {
          return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
      } 
        
    }


    /** 
     * WebHook Callback api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function hookCallback(Request $request) 
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                'payment_intent' => 'required',
                'counsellor_id' => 'required',  
                'package_id' => 'required', 
                'slot' => 'required', 
                'booking_date' => 'required',  
                'user' => 'required',
                //'notes' => 'required',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
            }

            $user = $request->user;

            Stripe\Stripe::setApiKey('sk_test_51IVk6mInEL6a47XwZYFPim5hOAN95WkN46LgAJHAMzu6FnnH1xPZ0C9HoK4xXRwtZiBWUrbX5OpKThxiO0HpmZsi001GW383pW');
            
            $stripe = new Stripe\StripeClient('sk_test_51IVk6mInEL6a47XwZYFPim5hOAN95WkN46LgAJHAMzu6FnnH1xPZ0C9HoK4xXRwtZiBWUrbX5OpKThxiO0HpmZsi001GW383pW');

            $conf = $stripe->paymentIntents->retrieve(
              $request->payment_intent,
              []
            );

            /*$conf = $stripe->paymentIntents->confirm(
              $request->payment_intent,
              []
            );*/
            
            $payment = new Payment;
            $payment->user_id = $user;

            $payment->charge_id = $conf->charges->data[0]->id;
            $payment->amount = $conf->charges->data[0]->amount;
            $payment->amount_captured = $conf->charges->data[0]->amount_captured;
            $payment->amount_refunded = $conf->charges->data[0]->amount_refunded;
            $payment->application = $conf->charges->data[0]->application;
            $payment->application_fee = $conf->charges->data[0]->application_fee;
            $payment->application_fee_amount = $conf->charges->data[0]->application_fee_amount;
            $payment->balance_transaction = $conf->charges->data[0]->balance_transaction;
            $payment->calculated_statement_descriptor = $conf->charges->data[0]->calculated_statement_descriptor;
            $payment->captured = $conf->charges->data[0]->captured;
            $payment->created = $conf->charges->data[0]->created;
            $payment->currency = $conf->charges->data[0]->currency;
            $payment->customer = $conf->charges->data[0]->customer;

            $payment->description = $conf->charges->data[0]->description;
            $payment->destination = $conf->charges->data[0]->destination;
            $payment->dispute = $conf->charges->data[0]->dispute;
            $payment->disputed = $conf->charges->data[0]->disputed;
            $payment->failure_code = $conf->charges->data[0]->failure_code;
            $payment->failure_message = $conf->charges->data[0]->failure_message;
            $payment->invoice = $conf->charges->data[0]->invoice;
            $payment->livemode = $conf->charges->data[0]->livemode;
            $payment->on_behalf_of = $conf->charges->data[0]->on_behalf_of;
            $payment->paid = $conf->charges->data[0]->paid;
            $payment->payment_intent = $conf->charges->data[0]->payment_intent;
            $payment->payment_method = $conf->charges->data[0]->payment_method;
            $payment->receipt_email = $conf->charges->data[0]->receipt_email;

            $payment->receipt_number = $conf->charges->data[0]->receipt_number;
            $payment->receipt_url = $conf->charges->data[0]->receipt_url;
            $payment->refunded = $conf->charges->data[0]->refunded;
            $payment->review = $conf->charges->data[0]->review;
            $payment->shipping = $conf->charges->data[0]->shipping;
            $payment->source = $conf->charges->data[0]->source->id;
            $payment->source_transfer = $conf->charges->data[0]->source_transfer;
            $payment->statement_descriptor = $conf->charges->data[0]->statement_descriptor;
            $payment->statement_descriptor_suffix = $conf->charges->data[0]->statement_descriptor_suffix;
            $payment->status = $conf->charges->data[0]->status;
            $payment->transfer = $conf->charges->data[0]->transfer;
            $payment->transfer_amount = $conf->charges->data[0]->transfer_data->amount;
            $payment->transfer_destination = $conf->charges->data[0]->transfer_data->destination;
            $payment->transfer_group = $conf->charges->data[0]->transfer_group;
            $payment->save();

           
            if($conf->status == 'succeeded')
            {

                $booking = new Booking; 
                $booking->user_id = $user;
                $booking->counsellor_id = $request->counsellor_id;
                $booking->slot = $request->slot;
                $booking->booking_date = $request->booking_date;
                $booking->package_id = $request->package_id;
                $booking->notes = $request->notes;
                $booking->status = '1';
                $booking->save();

                //checking existing channel data
                $checkExist = VideoChannel::where('from_id', $user)->where('to_id', $request->counsellor_id)->first();

                //saving video channel data
                if(empty($checkExist))
                {
                  $channelData = new VideoChannel; 
                  $channelData->from_id = $user;
                  $channelData->to_id = $request->counsellor_id;
                  $channelData->channel_id = $this->generateRandomString(20);
                  $channelData->timing = $request->timing;
                  //$channelData->uid = $request->uid;
                  $channelData->status = '0';
                  $channelData->save();
                }
                
                return response()->json(['success' => true,
                                         'message' => 'Your payment has been made successfully!',
                                        ], $this->successStatus); 
           }
           else
           {
                return response()->json(['success'=>false,'errors' =>['exception' => [$conf->status]]], $this->successStatus); 
           }
             

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
        
    }

    /** 
     * Get Past Booking api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getPastBooking(Request $request) 
    {
        // try
        // {
            
            $user = Auth::user();
            if($user->role_id == 2)
            {
                $allBookings = Booking::with('counsellor')->where('counsellor_id', $user->id)->where('status', '!=', '4')->get(); 
                $counsellorTimeZone = $user->timezone;

                
                if(count($allBookings) > 0)
                { 
                    
                    /*$pastBookings = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')
                    ->where('counsellor_id', $user->id)
                    ->where('booking_date', '<', Carbon::today($counsellorTimeZone))
                    ->orderBy('booking_date','DESC')
                    ->orderBy(DB::raw("STR_TO_DATE(slot,'%h.%i%A')"), 'ASC')
                    ->paginate(5);*/
                    $arr = [];

                    $currentTime = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now($counsellorTimeZone))->format('g:i A');
                    $time = date("H:i:s", strtotime($currentTime));

                    $pastBookings = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')
                    ->selectRaw("*, TIME_FORMAT(slot, '%H:%i') as timesort")
                    ->where('status', '!=', '4')
                    ->where('counsellor_id', $user->id)
                    ->where('booking_date', '<', Carbon::today($counsellorTimeZone))
                      ->orWhere(function ($query) use ($counsellorTimeZone, $time) {
                          $query->where('booking_date','=', Carbon::today($counsellorTimeZone))
                                ->whereTime('user_end_datetime', '<', $time);

                      })
                    ->orderBy(DB::raw("DATE(booking_date)"), 'DESC')
                    ->orderBy('user_start_datetime', 'DESC')
                    //->orderBy(DB::raw("STR_TO_DATE('user_start_datetime','%Y-%m-%d %H:%i:%s')"), 'DESC')
                    ->paginate(5);



                    
                    /*$currentTime = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now($counsellorTimeZone))->format('h:i a');

                    $pastBookings = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')
                    ->selectRaw("*, TIME_FORMAT(slot, '%H:%i') as timesort")
                    ->where('user_id', $user->id)
                    ->where('booking_date', '<', Carbon::today($counsellorTimeZone))
                    ->orderBy(DB::raw("DATE(booking_date)"), 'DESC')
                    ->orderBy('timesort', 'DESC')
                    ->paginate(5)->toArray();
                  

                    $newArr = [];
                    foreach($pastBookings['data'] as $key => $pastBooking)
                    {
                        if(!(($pastBookings['data'][$key]['booking_date'] == Carbon::today($counsellorTimeZone)->format('Y-m-d')) && (strtotime($pastBookings['data'][$key]['slot']) > strtotime($currentTime))))
                        {
                            $newArr[] = $pastBookings['data'][$key];
                        }
                    }*/
                    

                    //if(!empty($newArr)){
                      //$pastBookings['data'] = $newArr;
                    return response()->json(['success' => true,
                                            'past' => $pastBookings
                                        ], $this->successStatus);
                   //}
                }
                else
                {
                    return response()->json(['success'=>false,'errors' =>['exception' => ['']]], $this->successStatus);
                }
            }
            else
            {
                $allBookings = Booking::where('user_id', $user->id)->where('status', '!=', '4')->get(); 
                $userTimeZone = $user->timezone;

                if(count($allBookings) > 0)
                { 
                    $arr = [];
                    
                    $currentTime = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now($userTimeZone))->format('g:i A');
                    $time = date("H:i:s", strtotime($currentTime));

                    $pastBookings = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')
                    ->selectRaw("*, TIME_FORMAT(counsellor_timezone_slot, '%H:%i') as timesort")
                    ->where('status', '!=', '4')
                    ->where('user_id', $user->id)
                    ->where('counsellor_booking_date', '<', Carbon::today($userTimeZone))
                      ->orWhere(function ($query) use ($userTimeZone, $time) {
                          $query->where('counsellor_booking_date','=', Carbon::today($userTimeZone))
                                ->whereTime('counsellor_end_datetime', '<', $time);

                      })
                    ->orderBy(DB::raw("DATE(counsellor_booking_date)"), 'DESC')
                    ->orderBy('timesort', 'DESC')
                    ->paginate(5);
                    //->toSql();
                  

                    /*$newArr = [];
                    foreach($pastBookings['data'] as $key => $pastBooking)
                    {
                        $packageTime = Package::where('id', $pastBooking['package_id'])->first();
                        $utimesFrom = Carbon::parse($pastBookings['data'][$key]['counsellor_timezone_slot'])->addMinutes($packageTime->session_minutes)->format('g:i A');
                        
                        if(!(($pastBookings['data'][$key]['counsellor_booking_date'] == Carbon::today($userTimeZone)->format('Y-m-d')) && (strtotime($utimesFrom) > strtotime($currentTime))))
                        {
                            $newArr[] = $pastBookings['data'][$key];
                        }
                    }*/

                    //if(!empty($newArr)){
                      //$pastBookings['data'] = $newArr;
                      return response()->json(['success' => true,
                                         'past' => $pastBookings,
                                         //'array_split'=>$newArr
                                        ], $this->successStatus);
                    //}
                }
                else
                {
                    return response()->json(['success'=>false,'errors' =>['exception' => ['']]], $this->successStatus);
                }
            }
            
        //}
        // catch(\Exception $e)
        // {
        //     return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        // }  
        
    }

    /** 
     * Get Todays Booking api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getTodaysBooking(Request $request) 
    {
        try
        {
           
            $user = Auth::user();
            if($user->role_id == 2)
            {
                $allBookings = Booking::with('counsellor')->where('counsellor_id', $user->id)->where('status', '!=', '4')->get(); 
                $counsellorTimeZone = $user->timezone;

                if(count($allBookings) > 0)
                { 
                  if($request->page == -1)
                  {
                   
                    $todaysBooking = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')
                    ->selectRaw("*, TIME_FORMAT(slot, '%H:%i') as timesort")
                    ->where('counsellor_id', $user->id)
                    ->where('booking_date', Carbon::today($counsellorTimeZone))
                    ->where('status', '!=', '4')
                    //->orderBy(DB::raw("STR_TO_DATE(slot,'%h.%i%a')"), 'ASC')
                    ->orderBy('slot')
                    ->get();
                  }
                  else
                  {
                    
                    $todaysBooking = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')
                    ->selectRaw("*, TIME_FORMAT(slot, '%H:%i') as timesort")
                    ->where('counsellor_id', $user->id)
                    ->where('booking_date', Carbon::today($counsellorTimeZone))
                    ->where('status', '!=', '4')
                    //->orderBy(DB::raw("STR_TO_DATE(slot,'%h.%i%a')"), 'ASC')
                    ->orderBy('slot')
                    ->paginate(5);
                  }
                    

                    
                    return response()->json(['success' => true,
                                         'todays' => $todaysBooking
                                        ], $this->successStatus);
                }
                else
                {
                  
                    return response()->json(['success'=>false,'errors' =>['exception' => ['']]], $this->successStatus);
                }
            }
            else
            {
                $allBookings = Booking::where('user_id', $user->id)->where('status', '!=', '4')->get(); 
                $userTimeZone = $user->timezone;

                if(count($allBookings) > 0)
                { 
                  if($request->page == -1)
                  {
                    $todaysBooking = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')
                    ->selectRaw("*, TIME_FORMAT(counsellor_timezone_slot, '%H:%i') as timesort")
                    ->where('user_id', $user->id)
                    ->where('counsellor_booking_date', Carbon::today($userTimeZone))
                    ->where('status', '!=', '4')
                    //->orderBy(DB::raw("STR_TO_DATE(counsellor_timezone_slot,'%h.%i%a')"), 'ASC')
                    ->orderBy('counsellor_timezone_slot')
                    ->get();
                  }
                  else
                  {
                    $todaysBooking = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')
                    ->selectRaw("*, TIME_FORMAT(counsellor_timezone_slot, '%H:%i') as timesort")
                    ->where('user_id', $user->id)
                    ->where('counsellor_booking_date', Carbon::today($userTimeZone))
                    ->where('status', '!=', '4')
                    //->orderBy('counsellor_timezone_slot','ASC')
                    //->orderBy(DB::raw("STR_TO_DATE(counsellor_timezone_slot,'%h.%i%a')"), 'ASC')
                    ->orderBy('counsellor_timezone_slot')
                    ->paginate(5);
                  }

                    return response()->json(['success' => true,
                                         'todays' => $todaysBooking
                                        ], $this->successStatus);
                }
                else
                {
                    /*return response()->json(['success' => false,
                                         'message' => 'No bookings found',
                                        ], $this->successStatus);*/

                    return response()->json(['success'=>false,'errors' =>['exception' => ['']]], $this->successStatus);
                }
            }
            
            
             

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        }  
        
    }

    /** 
     * Get Upcoming Booking api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getUpcomingBooking(Request $request) 
    {
        try
        {
            $user = Auth::user();
            
            if($user->role_id == 2)
            {
                $allBookings = Booking::with('counsellor')->where('counsellor_id', $user->id)->where('status','!=','4')->get(); 
                $counsellorTimeZone = $user->timezone;

                $currentCounsellorTime  = Carbon::now($counsellorTimeZone);
                $currentTime = $currentCounsellorTime->format('H:i:s');

                if(count($allBookings) > 0)
                { 
                   
                    $todaysUpcoming = Booking::with('counsellor','package','user')
                    ->where('counsellor_id', $user->id)
                    ->where('booking_date', '=', Carbon::today($counsellorTimeZone))
                    ->where('status','!=','4')
                    ->get();

                    $common = [];
                    $commonPast = [];
                    foreach($todaysUpcoming as $todayUpcoming)
                    {
                      //$time = date("H:i:s", strtotime($todayUpcoming->slot));
                      $time = Carbon::parse($todayUpcoming->slot)->format("H:i:s");
                      
                      if( ($time > $currentTime))
                      { 
                        array_push($common, $todayUpcoming->id);
                      }   
                      else
                      {
                        array_push($commonPast, $todayUpcoming->id);
                      }                   
                    }

                    $upcomingBookings = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')
                    ->where('status','!=','4')
                    ->where('counsellor_id', $user->id)
                    ->selectRaw("*, TIME_FORMAT(slot, '%H:%i') as timesort")

                    ->where(function ($query) use ($counsellorTimeZone){
                        $query->where('booking_date', '>', Carbon::today($counsellorTimeZone));
                    })->oRwhere(function ($query) use ($common) {
                        $query->whereIn('id', $common);
                    })

                    ->orderBy('booking_date','ASC')
                    
                    //->orderBy(DB::raw("STR_TO_DATE(slot,'%h.%i%a')"), 'ASC')
                    ->orderBy('slot')
                    //->orderBy(DB::raw("STR_TO_DATE(counsellor_timezone_slot,'%l:%i %p')"), 'ASC')
                    //->orderBy('slot','ASC')
                    ->paginate(5);
                    


                    return response()->json(['success' => true,
                                         'upcoming' => $upcomingBookings
                                        ], $this->successStatus);
                }
                   
                return response()->json(['success'=>false,'errors' =>['exception' => ['']]], $this->successStatus);
                
            }
            else
            {
                $userTimeZone = $user->timezone;
                $allBookings = Booking::where('user_id', $user->id)->where('status','!=','4')->get(); 
                $currentTime  = Carbon::now($userTimeZone)->format('H:i:s');
                
                
                if(count($allBookings) > 0)
                { 
                   
                    $todaysUpcoming = Booking::with('counsellor','package','user')
                    ->where('user_id', $user->id)
                    ->where('counsellor_booking_date', '=', Carbon::today($userTimeZone))
                    ->where('status','!=','4')
                    ->get();
                    $common = [];
                    $commonPast = [];
                    foreach($todaysUpcoming as $todayUpcoming)
                    {
                      //$time = date("H:i:s", strtotime($todayUpcoming->slot));
                      $time = Carbon::parse($todayUpcoming->counsellor_timezone_slot)->format("H:i:s");
                      
                      if( ($time > $currentTime))
                      { 
                        array_push($common, $todayUpcoming->id);
                      }
                      else
                      {
                        array_push($commonPast, $todayUpcoming->id); 
                      }                      
                    }


                    $upcomingBooking = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')
                    ->selectRaw("*, TIME_FORMAT(counsellor_timezone_slot, '%H:%i') as timesort")
                    ->where('user_id', $user->id)
                    ->where('status','!=','4')
                    
                    ->where(function ($query) use ($userTimeZone){
                        $query->where('counsellor_booking_date', '>', Carbon::today($userTimeZone));
                    })->oRwhere(function ($query) use ($common) {
                        $query->whereIn('id', $common);
                    })
                    ->orderBy('counsellor_booking_date','ASC')
                    //->orderBy(DB::raw("STR_TO_DATE(counsellor_timezone_slot,'%h.%i%a')"), 'ASC')
                    ->orderBy('counsellor_timezone_slot','ASC')
                    //->orderBy(DB::raw("STR_TO_DATE(counsellor_timezone_slot,'%h.%i%a')"), 'ASC')
                    //->orderBy('counsellor_timezone_slot','ASC')
                    ->paginate(5);
                    //->get();

                    return response()->json(['success' => true,
                                         'upcoming' => $upcomingBooking
                                        ], $this->successStatus);
                }
                    
                return response()->json(['success'=>false,'errors' =>['exception' => ['']]], $this->successStatus);
                
            }
            
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        }  
        
    }
    public function leftsession(Request $request)
    {
        try
        {
            $user = Auth::user();
            $myLeftSessions=LeftSession::where('user_id',$user->id)->where('left_sessions', '>' , 0)->get();
            if(count($myLeftSessions) > 0)
            {
                $getMyPlannedSessions = LeftSession::with('package')->with('package.user:id,name,email,avatar_id')->where('user_id',$user->id)->paginate(5);
                foreach($getMyPlannedSessions as $key => $myPlannedSession)
                {
                    $bookingDetails = Booking::where('payment_id', $myPlannedSession->payment_id)->where('status', '!=', '4')->orderBy('id', 'DESC')->first();
                    $getMyPlannedSessions[$key]->last_appointment_date = !empty($bookingDetails->booking_date) ? $bookingDetails->booking_date : "";

                    $getMyPlannedSessions[$key]->last_appointment_slot = !empty($bookingDetails->counsellor_timezone_slot) ? $bookingDetails->counsellor_timezone_slot : "";
                }

                return response()->json(['success' => true,
                                         'leftsession' => $getMyPlannedSessions
                                        ], $this->successStatus);
            }
            else
            {
                return response()->json(['success'=>false,'errors' =>['exception' => ['We did not found any planned session']]], $this->successStatus);
            }
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        }
    }

    /** 
     * Get Current Week Booking api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getCurrentWeekBooking(Request $request) 
    {
        try
        {
            
            $user = Auth::user();
            if($user->role_id == 2)
            {
                $counsellorTimeZone = $user->timezone;
                $allBookings = Booking::where('counsellor_id', $user->id)->where('status', '!=', '4')->get(); 

                if(count($allBookings) > 0)
                { 
                    $currentWeekBooking = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')->where('counsellor_id', $user->id)
                    ->where('status', '!=', '4')
                    ->where('booking_date', '>', Carbon::now($counsellorTimeZone)->startOfWeek(Carbon::SUNDAY))
                    ->where('booking_date', '<', Carbon::now($counsellorTimeZone)->endOfWeek(Carbon::SATURDAY))
                    ->orderBy('booking_date','ASC')
                    ->paginate(5);
                    //->get();

                    return response()->json(['success' => true,
                                         'current_week' => $currentWeekBooking
                                        ], $this->successStatus);
                }
                else
                {
                   
                    return response()->json(['success'=>false,'errors' =>['exception' => ['']]], $this->successStatus);
                }
            }
            else
            {
                $userTimeZone = $user->timezone;
                $allBookings = Booking::where('user_id', $user->id)->where('status', '!=', '4')->get(); 

                if(count($allBookings) > 0)
                { 
                   
                    $currentWeekBooking = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')->where('user_id', $user->id)
                    ->where('status', '!=', '4')
                    ->where('counsellor_booking_date', '>', Carbon::now($userTimeZone)->startOfWeek(Carbon::SUNDAY))
                    ->where('counsellor_booking_date', '<', Carbon::now($userTimeZone)->endOfWeek(Carbon::SATURDAY))
                    ->orderBy('counsellor_booking_date','ASC')
                   ->paginate(5);
                    //->get();

                    return response()->json(['success' => true,
                                         'current_week' => $currentWeekBooking
                                        ], $this->successStatus);
                }
                else
                {
                    
                    return response()->json(['success'=>false,'errors' =>['exception' => ['']]], $this->successStatus);
                }
            }
           
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
            $currentTime  = Carbon::now('Asia/Kolkata');
            $currentTime = $currentTime->format('H:i:s');
            $currentDate = date('y-m-d');
            
            $user = Auth::user();
            if($user->role_id == 2)
            {
                $allBookings = Booking::where('counsellor_id', $user->id)->get(); 

                if(count($allBookings) > 0)
                { 
                    $pastBookings = Booking::with('counsellor','package','user')
                    ->where('counsellor_id', $user->id)
                    ->where('booking_date', '<', Carbon::today())
                    ->orderBy('booking_date','DESC')
                    ->orderBy('slot','ASC')
                    ->paginate(5);
                    //->get();

                    $todaysBooking = Booking::with('counsellor','package','user')
                    ->where('counsellor_id', $user->id)
                    ->where('booking_date', Carbon::today())
                    ->orderBy('slot','ASC')
                    ->paginate(5);
                    //->get();

                    


                    $todaysUpcoming = Booking::with('counsellor','package','user')
                    ->where('counsellor_id', $user->id)
                    ->where('booking_date', '=', Carbon::today())
                    ->get();
                    $common = [];
                    $commonPast = [];
                    foreach($todaysUpcoming as $todayUpcoming)
                    {
                      $time = date("H:i:s", strtotime($todayUpcoming->slot));
                      
                      if( ($time > $currentTime))
                      { 
                        array_push($common, $todayUpcoming->id);
                      }   
                      else
                      {
                        array_push($commonPast, $todayUpcoming->id);
                      }                   
                    }




                    $upcomingBookings = Booking::with('counsellor','package','user')
                    ->where('counsellor_id', $user->id)

                    ->where(function ($query) {
                        $query->where('booking_date', '>', Carbon::today());
                    })->oRwhere(function ($query) use ($common) {
                        $query->whereIn('id', $common);
                    })

                    ->orderBy('booking_date','ASC')
                    ->orderBy('slot','ASC')
                    ->paginate(5);
                    //->get();





                    $tdyUpcoming = Booking::with('counsellor','package','user')
                    ->whereIn('id', $common)
                    ->orderBy('id', 'ASC')
                    ->paginate(5);
                    //->get();

                    $tdyPast = Booking::with('counsellor','package','user')
                    ->whereIn('id', $commonPast)
                    ->orderBy('id', 'DESC')
                    ->paginate(5);
                    //->get();

                    /*$upcomingBooking = Booking::with('counsellor','package','user')
                    ->where('counsellor_id', $user->id)


                     ->where(function ($query) use ($currentTime) {
                          $query->where('booking_date', '>', Carbon::today())
                          ->where('slot', '>', $currentTime);
                      })->oRwhere(function ($query) {
                          $query->where('booking_date', '>', Carbon::today());
                      })
                      ->get();*/

                    $currentWeekBooking = Booking::with('counsellor','package','user')->where('counsellor_id', $user->id)
                    ->where('booking_date', '>', Carbon::now()->startOfWeek(Carbon::SUNDAY))
                    ->where('booking_date', '<', Carbon::now()->endOfWeek(Carbon::SATURDAY))
                    ->orderBy('booking_date','ASC')
                    ->paginate(5);
                    //->get();

                    return response()->json(['success' => true,
                                         'past' => $pastBookings,
                                         'todays' => $todaysBooking,
                                         //'todays_upcoming' => $tdyUpcoming,
                                         //'todays_past' => $tdyPast,
                                         'upcoming' => $upcomingBookings,
                                         'current_week' => $currentWeekBooking,
                                        ], $this->successStatus);
                }
                else
                {
                    /*return response()->json(['success' => false,
                                         'message' => 'No bookings found',
                                        ], $this->successStatus);*/

                    return response()->json(['success'=>false,'errors' =>['exception' => ['']]], $this->successStatus);
                }
            }
            else
            {
                $allBookings = Booking::where('user_id', $user->id)->get(); 

                if(count($allBookings) > 0)
                { 
                    $pastBookings = Booking::with('counsellor','package','user')
                    ->where('user_id', $user->id)
                    ->where('booking_date', '<', Carbon::today())
                    ->orderBy('booking_date','DESC')
                    ->orderBy('slot','ASC')
                    ->paginate(5);
                    //->get();

                    $todaysBooking = Booking::with('counsellor','package','user')
                    ->where('user_id', $user->id)
                    ->where('booking_date', Carbon::today())
                    ->orderBy('slot','ASC')
                    ->paginate(5);
                    //->get();

                    

                    $todaysUpcoming = Booking::with('counsellor','package','user')
                    ->where('user_id', $user->id)
                    ->where('booking_date', '=', Carbon::today())
                    ->get();
                    $common = [];
                    $commonPast = [];
                    foreach($todaysUpcoming as $todayUpcoming)
                    {
                      $time = date("H:i:s", strtotime($todayUpcoming->slot));
                      
                      if( ($time > $currentTime))
                      { 
                        array_push($common, $todayUpcoming->id);
                      }
                      else
                      {
                        array_push($commonPast, $todayUpcoming->id); 
                      }                      
                    }


                    $upcomingBooking = Booking::with('counsellor','package','user')
                    ->where('user_id', $user->id)
                    
                    ->where(function ($query) {
                        $query->where('booking_date', '>', Carbon::today());
                    })->oRwhere(function ($query) use ($common) {
                        $query->whereIn('id', $common);
                    })
                    ->orderBy('booking_date','ASC')
                    ->orderBy('slot','ASC')
                    ->paginate(5);
                    //->get();

                    $tdyUpcoming = Booking::with('counsellor','package','user')
                    ->whereIn('id', $common)
                    ->orderBy('id', 'ASC')
                    ->paginate(5);
                    //->get();

                    $tdyPast = Booking::with('counsellor','package','user')
                    ->whereIn('id', $commonPast)
                    ->orderBy('id', 'DESC')
                    ->paginate(5);
                    //->get();

                    $currentWeekBooking = Booking::with('counsellor','package','user')->where('user_id', $user->id)
                    ->where('booking_date', '>', Carbon::now()->startOfWeek())
                    ->where('booking_date', '<', Carbon::now()->endOfWeek())
                    ->orderBy('booking_date','ASC')
                   ->paginate(5);
                    //->get();

                    return response()->json(['success' => true,
                                         'past' => $pastBookings,
                                         'todays' => $todaysBooking,
                                         //'todays_upcoming' => $tdyUpcoming,
                                         //'todays_past' => $tdyPast,
                                         'upcoming' => $upcomingBooking,
                                         'current_week' => $currentWeekBooking,
                                        ], $this->successStatus);
                }
                else
                {
                    /*return response()->json(['success' => false,
                                         'message' => 'No bookings found',
                                        ], $this->successStatus);*/

                    return response()->json(['success'=>false,'errors' =>['exception' => ['']]], $this->successStatus);
                }
            }
            
            
             

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        }  
        
    }

    /** 
     * Confirm Booking
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function confirmBooking(Request $request) 
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                'booking_id' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
            }

            $user = Auth::user();
            
            //counsellor
            if($user->role_id == 2)
            {
              $booking = Booking::where('id', $request->booking_id)->where('counsellor_id', $user->id)->first();

              if(!empty($booking))
              {
                if($request->status == 3)
                {
                  $booking->status = '3'; 
                  $booking->save();

                  $bookingStatus = Booking::where('id', $request->booking_id)->where('counsellor_id', $user->id)->first(); 

                  return response()->json(['success' => true,
                                           'message' => 'Booking confirmed!',
                                           'data'    => $bookingStatus,
                                          ], $this->successStatus);
                }
                elseif($request->status == 2)
                {
                  $booking->status = '2'; 
                  $booking->save();

                  $bookingStatus = Booking::where('id', $request->booking_id)->where('counsellor_id', $user->id)->first(); 

                  //Send Mail
                
                  /*event(new CancelBookingByCounsellorEvent($bookingStatus->id, $bookingStatus->counsellor_id, $bookingStatus->user_id));*/

                  return response()->json(['success' => true,
                                           'message' => 'Booking cancelled!',
                                           'data'    => $bookingStatus,
                                          ], $this->successStatus);
                }
                else
                {
                  return response()->json(['success'=>false,'errors' =>['exception' => ['Invalid status code']]], $this->successStatus); 
                }
                  
              }
              else
              {
                  /*return response()->json(['success' => false,
                                           'message' => 'No bookings found with this booking ID',
                                          ], $this->successStatus);*/

                  return response()->json(['success'=>false,'errors' =>['exception' => ['No bookings found with this booking ID']]], $this->successStatus);
              }
            }
            elseif($user->role_id == 3) //user
            {
              $booking = Booking::where('id', $request->booking_id)->where('user_id', $user->id)->first();

              if(!empty($booking))
              {
                
                if($request->status == 2)
                {
                  $booking->status = '2'; 
                  $booking->save();

                  $bookingStatus = Booking::where('id', $request->booking_id)->where('user_id', $user->id)->first(); 

                  return response()->json(['success' => true,
                                           'message' => 'Booking cancelled!',
                                           'data'    => $bookingStatus,
                                          ], $this->successStatus);
                }
                else
                {
                  return response()->json(['success'=>false,'errors' =>['exception' => ['Invalid status code']]], $this->successStatus); 
                }
                  
              }
              else
              {
                  /*return response()->json(['success' => false,
                                           'message' => 'No bookings found with this booking ID',
                                          ], $this->successStatus);*/

                  return response()->json(['success'=>false,'errors' =>['exception' => ['No bookings found with this booking ID']]], $this->successStatus);
              }
            }
            else
            {
              return response()->json(['success'=>false,'errors' =>['exception' => ['Invalid User']]], $this->successStatus);
            }
           
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
        } 
            
            
        
    }

    /** 
     * Get counsellor booking
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function allBookings(Request $request) 
    {
        try
        {
            
            $user = Auth::user();
            if($user->role_id == 2)
            {
              $booking = Booking::with('counsellor:id,name,email,timezone')->with('package')->with('user:id,name,email')->where('counsellor_id', $user->id)->orderBy('booking_date', 'DESC')
                ->withTrashed()
                ->get();
            }
            else
            {
              $booking = Booking::with('counsellor:id,name,email,timezone')->with('package')->with('user:id,name,email')->where('user_id', $user->id)->orderBy('counsellor_booking_date', 'DESC')
              ->withTrashed()
              ->get();
            }
            
            if(count($booking) > 0)
            {
              
                return response()->json(['success' => true,
                                         'bookings' => $booking,
                                        ], $this->successStatus);
            }
            else
            {
                /*return response()->json(['success' => false,
                                         'message' => 'No bookings found',
                                        ], $this->successStatus);*/

                return response()->json(['success'=>false,'errors' =>['exception' => ['']]], $this->successStatus);
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

    /*
     * Validate Data
     * @Params $requestedfields
     */

    public function validateData($requestedFields){
        $rules = [];

        foreach ($requestedFields as $key => $field) {
            //return $key;
            if($key == 'counsellor_id'){

                $rules[$key] = 'required';

            }else if($key == 'package_id'){

                $rules[$key] = 'required';

            }else if($key == 'slot'){

                $rules[$key] = 'required';

            }else if($key == 'booking_date'){

                $rules[$key] = 'required';

            }else if($key == 'token'){

                $rules[$key] = 'required';

            }else if($key == 'card_id'){

                $rules[$key] = 'required';

            }
        }

        return $rules;

    }

    
    /*
     * Validate Cart Slots
     * @Params $requestedfields
     */

    public function validateCartSlots($requestedFields){
        $rules = [];

        foreach ($requestedFields as $key => $field) {
            //return $key;
            if($key == 'counsellor_id'){

                $rules[$key] = 'required';

            }else if($key == 'package_id'){

                $rules[$key] = 'required';

            }else if($key == 'slot'){

                $rules[$key] = 'required';

            }else if($key == 'booking_date'){

                $rules[$key] = 'required';

            }
        }

        return $rules;

    }

    /*
     * Validate Delete Slots
     * @Params $requestedfields
     */

    public function validateSlots($requestedFields){
        $rules = [];

        foreach ($requestedFields as $key => $field) {
            //return $key;
            if($key == 'counsellor_id'){

                $rules[$key] = 'required';

            }else if($key == 'package_id'){

                $rules[$key] = 'required';

            }else if($key == 'booking_date'){

                $rules[$key] = 'required';

            }
        }

        return $rules;

    }

    public function sendSMS($countryCode, $phone)
    {
        $sid = env('ACCOUNT_SID'); // Your Account SID from www.twilio.com/console
        $token = env('AUTH_TOKEN'); // Your Auth Token from www.twilio.com/console
        $from = env('FROM_NUMBER_TWILLIO'); // Your Auth Token from www.twilio.com/console
        $message = "Hi, Your payment has been done successfully!";

        //$client = new Client('AC953054f1d913bc6c257f904f2b4ef2b0', '4f9fc49a2cf382f4bb801f47c425f7e9');
        $client = new Client($sid, $token);
        $message = $client->messages->create(
          $countryCode.''.$phone, // Text this number
          [
            //'from' => '+15005550006', // From a valid Twilio number
            'from' => $from,
            'body' => $message
          ]
        );
    }

    public function failedBookingSMS($countryCode, $phone)
    {
        $sid = env('ACCOUNT_SID'); // Your Account SID from www.twilio.com/console
        $token = env('AUTH_TOKEN'); // Your Auth Token from www.twilio.com/console
        $from = env('FROM_NUMBER_TWILLIO'); // Your Auth Token from www.twilio.com/console
        $message = "Hi, Your payment was failed!";

        //$client = new Client('AC953054f1d913bc6c257f904f2b4ef2b0', '4f9fc49a2cf382f4bb801f47c425f7e9');
        $client = new Client($sid, $token);
        $message = $client->messages->create(
          $countryCode.''.$phone, // Text this number
          [
            //'from' => '+15005550006', // From a valid Twilio number
            'from' => $from,
            'body' => $message
          ]
        );
    }


}
