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
use App\Payment;
use DB;
use Event;
use Stripe;
use Carbon\Carbon;
use Twilio\Rest\Client;
use App\Events\UserRegisterEvent;
use App\Events\BookingEvent;
use App\Events\BookingCounsellorEvent;
use App\Events\FailedBookingEvent;
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
              'amount' => $netAmt * 100,
              'description' => 'yoursafespaceonline.com',
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
              foreach($params['selected_slots'] as $date => $slots)
              {                     
                //array_push($slotArray, $slot);
                if(count($slots) > 0)
                {
                  foreach($slots as $slot)
                  {
                    $booking = new Booking; 
                    $booking->user_id = $user->id;
                    $booking->payment_id = $payment->id;
                    $booking->counsellor_id = $params['counsellor_id'];

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
                    $booking->notes = $params['notes'];
                    $booking->status = '1';
                    $booking->save();
                  }
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
                $userBody = "Your booking for ".$packageDetail->package_name." Package has been successfull.";
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
                
                //event(new BookingEvent($booking->id, $user->id));

                //Send Mail
                
                //event(new BookingCounsellorEvent($booking->id, $params['counsellor_id'], $user->id));

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
        try
        {
            
            $user = Auth::user();
            if($user->role_id == 2)
            {
                $allBookings = Booking::with('counsellor')->where('counsellor_id', $user->id)->get(); 
                $counsellorTimeZone = $user->timezone;

                
                if(count($allBookings) > 0)
                { 
                    
                    $pastBookings = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')
                    ->where('counsellor_id', $user->id)
                    ->where('booking_date', '<', Carbon::today($counsellorTimeZone))
                    ->orderBy('booking_date','DESC')
                    ->orderBy(DB::raw("STR_TO_DATE(slot,'%h.%i%A')"), 'ASC')
                    ->paginate(5);
                    


                    return response()->json(['success' => true,
                                            'past' => $pastBookings
                                        ], $this->successStatus);
                }
                else
                {
                    return response()->json(['success'=>false,'errors' =>['exception' => ['']]], $this->successStatus);
                }
            }
            else
            {
                $allBookings = Booking::where('user_id', $user->id)->get(); 
                $userTimeZone = $user->timezone;

                if(count($allBookings) > 0)
                { 
                    $pastBookings = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')
                    ->where('user_id', $user->id)
                    ->where('counsellor_booking_date', '<', Carbon::today($userTimeZone))
                    ->orderBy('counsellor_booking_date','DESC')
                    ->orderBy(DB::raw("STR_TO_DATE(counsellor_timezone_slot,'%h.%i%A')"), 'ASC')
                    ->paginate(5);
                    //->get();

                    return response()->json(['success' => true,
                                         'past' => $pastBookings
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
                $allBookings = Booking::with('counsellor')->where('counsellor_id', $user->id)->get(); 
                $counsellorTimeZone = $user->timezone;

                if(count($allBookings) > 0)
                { 
                  if($request->page == -1)
                  {
                   
                    $todaysBooking = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')
                    ->where('counsellor_id', $user->id)
                    ->where('booking_date', Carbon::today($counsellorTimeZone))
                    ->orderBy(DB::raw("STR_TO_DATE(slot,'%h.%i%A')"), 'ASC')
                    ->get();
                  }
                  else
                  {
                    
                    $todaysBooking = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')
                    ->where('counsellor_id', $user->id)
                    ->where('booking_date', Carbon::today($counsellorTimeZone))
                    ->orderBy(DB::raw("STR_TO_DATE(slot,'%h.%i%A')"), 'ASC')
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
                $allBookings = Booking::where('user_id', $user->id)->get(); 
                $userTimeZone = $user->timezone;

                if(count($allBookings) > 0)
                { 
                  if($request->page == -1)
                  {
                    $todaysBooking = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')
                    ->where('user_id', $user->id)
                    ->where('counsellor_booking_date', Carbon::today($userTimeZone))
                    ->orderBy(DB::raw("STR_TO_DATE(counsellor_timezone_slot,'%h.%i%A')"), 'ASC')
                    ->get();
                  }
                  else
                  {
                    $todaysBooking = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')
                    ->where('user_id', $user->id)
                    ->where('counsellor_booking_date', Carbon::today($userTimeZone))
                    //->orderBy('counsellor_timezone_slot','ASC')
                    ->orderBy(DB::raw("STR_TO_DATE(counsellor_timezone_slot,'%h.%i%A')"), 'ASC')
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
                $allBookings = Booking::with('counsellor')->where('counsellor_id', $user->id)->get(); 
                $counsellorTimeZone = $user->timezone;

                $currentCounsellorTime  = Carbon::now($counsellorTimeZone);
                $currentTime = $currentCounsellorTime->format('H:i:s');

                if(count($allBookings) > 0)
                { 
                   
                    $todaysUpcoming = Booking::with('counsellor','package','user')
                    ->where('counsellor_id', $user->id)
                    ->where('booking_date', '=', Carbon::today($counsellorTimeZone))
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
                    ->where('counsellor_id', $user->id)

                    ->where(function ($query) use ($counsellorTimeZone){
                        $query->where('booking_date', '>', Carbon::today($counsellorTimeZone));
                    })->oRwhere(function ($query) use ($common) {
                        $query->whereIn('id', $common);
                    })

                    ->orderBy('booking_date','ASC')
                    ->orderBy(DB::raw("STR_TO_DATE(slot,'%h.%i%A')"), 'ASC')
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
                $allBookings = Booking::where('user_id', $user->id)->get(); 
                $currentTime  = Carbon::now($userTimeZone)->format('H:i:s');
                
                
                if(count($allBookings) > 0)
                { 
                   
                    $todaysUpcoming = Booking::with('counsellor','package','user')
                    ->where('user_id', $user->id)
                    ->where('counsellor_booking_date', '=', Carbon::today($userTimeZone))
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
                    ->where('user_id', $user->id)
                    
                    ->where(function ($query) use ($userTimeZone){
                        $query->where('counsellor_booking_date', '>', Carbon::today($userTimeZone));
                    })->oRwhere(function ($query) use ($common) {
                        $query->whereIn('id', $common);
                    })
                    ->orderBy('counsellor_booking_date','ASC')
                    ->orderBy(DB::raw("STR_TO_DATE(counsellor_timezone_slot,'%h.%i%A')"), 'ASC')
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
                $allBookings = Booking::where('counsellor_id', $user->id)->get(); 

                if(count($allBookings) > 0)
                { 
                    $currentWeekBooking = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')->where('counsellor_id', $user->id)
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
                $allBookings = Booking::where('user_id', $user->id)->get(); 

                if(count($allBookings) > 0)
                { 
                   
                    $currentWeekBooking = Booking::with('counsellor','package','user','listing.listing_category','listing.listing_label','listing.listing_region','listing.gallery')->where('user_id', $user->id)
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
