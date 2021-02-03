<?php
 
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\Booking;
use App\StripeConnect;
use App\Availability;
use App\User;
use App\VideoChannel;
use App\Payment;
use Event;
use Stripe;
use Carbon\Carbon;
use Twilio\Rest\Client;
use App\Events\UserRegisterEvent;
use App\Events\BookingEvent;
use App\Events\BookingCounsellorEvent;
use App\Events\FailedBookingEvent;

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
              'slot' => 'required|max:190', 
              'booking_date' => 'required',
              'token' => 'required',
              'card_id' => 'required', 
              //'notes' => 'required',
          ]);

      if ($validator->fails()) 
            { 
              return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
      }
            $user = Auth::user();
            
            $packageAmt = Package::where('id', $request->package_id)->first();

            //$connectedActID = StripeConnect::where('user_id', $request->counsellor_id)->first();

            Stripe\Stripe::setApiKey('sk_test_51HeJy8FLGFzxhmLyc7WD0MjMrLNiXexvbyiYelajGk7OZF8Mvh3y2NUWEIX2XuTfQG2txpl3N38yYSva0qqz7lkj00qOEAhKE9');
            
            $stripe = new Stripe\StripeClient('sk_test_51HeJy8FLGFzxhmLyc7WD0MjMrLNiXexvbyiYelajGk7OZF8Mvh3y2NUWEIX2XuTfQG2txpl3N38yYSva0qqz7lkj00qOEAhKE9');

            /*Stripe\Stripe::setApiKey('sk_test_4QAdALiSUXZHzF1luppxZbsW00oaSZCQnZ');
            $stripe = new \Stripe\StripeClient('sk_test_4QAdALiSUXZHzF1luppxZbsW00oaSZCQnZ');*/


             /*$token = $stripe->tokens->create([
              'card' => [
                'number' => '4242424242424242',
                'exp_month' => '09',
                'exp_year' => '2024',
                'cvc' => '070',
              ],
            ]);*/

            


            /*$pI = $stripe->paymentIntents->retrieve(
              'pi_1HjdaiDONzaKgGcKY6VGFo22',
              []
            );

            return $pI;*/

            $customer = \Stripe\Customer::create(array(
                'name' => $user->name,
                'email' => $user->email,
            ));
            
           
           
            
            /*$method = $stripe->paymentMethods->create([
              'type' => 'card',
              'card' => [
                'number' => '23214324',
                'exp_month' => '05',
                'exp_year' => '2021',
                'cvc' => '568',
              ],
            ]);*/
            
            

            $source = \Stripe\Customer::createSource(
            $customer->id,
            //['source' => $token->id]);
            ['source' => $request->token]);

            

            $conf = \Stripe\PaymentIntent::create([
              //'amount' => $packageAmt->amount*100,
              'amount' => $packageAmt->amount * 100,
              'description' => 'Yoursafespaceonline.com',
              'customer' => $customer->id,
              'currency' => 'GBP',
              //'source' => $token->card->id, 
              //'source' => $request->card_id, 
              'confirmation_method' => 'manual',
              'confirm' => true,
              //'application_fee_amount' => 50,
              /*'transfer_data' => [
                'amount' => 1*100,
                'destination' => $connectedActID->stripe_id
              ],*/
            ]);

            //return $payment_intent;

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
            /*$payment->transfer_amount = $conf->charges->data[0]->transfer_data->amount;
            $payment->transfer_destination = $conf->charges->data[0]->transfer_data->destination;
            $payment->transfer_group = $conf->charges->data[0]->transfer_group;*/
            $payment->save();

           
            if($conf->status == 'succeeded')
            {

                $booking = new Booking; 
                $booking->user_id = $user->id;
                $booking->payment_id = $payment->id;
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
                  $channelData->from_id = $user->id;
                  $channelData->booking_id = $booking->id;
                  $channelData->to_id = $request->counsellor_id;
                  $channelData->channel_id = $this->generateRandomString(20);
                  $channelData->timing = $request->timing;
                  //$channelData->uid = $request->uid;
                  $channelData->status = '0';
                  $channelData->save();
                }

                //Send Otp Over Mail
                
                event(new BookingEvent($booking->id, $user->id));

                //Send Mail
                
                event(new BookingCounsellorEvent($booking->id, $request->counsellor_id, $user->id));

                //send sms for successful booking
                if(!empty($user->phone) && !empty($user->country_code))
                $this->sendSMS('+'.$user->country_code, $user->phone);
                
                return response()->json(['success' => true,
                                         'message' => 'Your payment has been made successfully!',
                                        ], $this->successStatus); 
           }
           else
           {
                //Send Mail
                
                event(new FailedBookingEvent($booking->id, $user->id));
                //send sms for successful booking

                if(!empty($user->phone) && !empty($user->country_code))
                $this->failedBookingSMS('+'.$user->country_code, $user->phone);

                return response()->json(['success'=>false,'errors' =>['exception' => [$conf->status]]], $this->successStatus); 
           }



            return response()->json(['success' => true,
             'data' => $conf,
           ], $this->successStatus);

            
            
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

            Stripe\Stripe::setApiKey('sk_test_51HeJy8FLGFzxhmLyc7WD0MjMrLNiXexvbyiYelajGk7OZF8Mvh3y2NUWEIX2XuTfQG2txpl3N38yYSva0qqz7lkj00qOEAhKE9');
            
            $stripe = new Stripe\StripeClient('sk_test_51HeJy8FLGFzxhmLyc7WD0MjMrLNiXexvbyiYelajGk7OZF8Mvh3y2NUWEIX2XuTfQG2txpl3N38yYSva0qqz7lkj00qOEAhKE9');

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

                    


                    $upcomingBookings = Booking::with('counsellor','package','user')
                    ->where('counsellor_id', $user->id)
                    ->where('booking_date', '>', Carbon::today())
                    ->orderBy('booking_date','ASC')
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
                                         'todays_upcoming' => $tdyUpcoming,
                                         'todays_past' => $tdyPast,
                                         'upcoming' => $upcomingBookings,
                                         'current_week' => $currentWeekBooking,
                                        ], $this->successStatus);
                }
                else
                {
                    /*return response()->json(['success' => false,
                                         'message' => 'No bookings found',
                                        ], $this->successStatus);*/

                    return response()->json(['success'=>false,'errors' =>['exception' => ['No bookings found']]], $this->successStatus);
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

                    $upcomingBooking = Booking::with('counsellor','package','user')
                    ->where('user_id', $user->id)
                    ->where('booking_date', '>', Carbon::today())
                    ->orderBy('booking_date','ASC')
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
                                         'todays_upcoming' => $tdyUpcoming,
                                         'todays_past' => $tdyPast,
                                         'upcoming' => $upcomingBooking,
                                         'current_week' => $currentWeekBooking,
                                        ], $this->successStatus);
                }
                else
                {
                    /*return response()->json(['success' => false,
                                         'message' => 'No bookings found',
                                        ], $this->successStatus);*/

                    return response()->json(['success'=>false,'errors' =>['exception' => ['No bookings found']]], $this->successStatus);
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
            elseif($user->role_id == 3)
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
                  return response()->json(['success'=>false,'errors' =>['exception' => ['Invalid status code'], $this->successStatus); 
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
              $booking = Booking::with('counsellor:id,name,email')->with('package')->with('user:id,name,email')->where('counsellor_id', $user->id)->orderBy('booking_date', 'DESC')
                ->withTrashed()
                ->get();
            }
            else
            {
              $booking = Booking::with('counsellor:id,name,email')->with('package')->with('user:id,name,email')->where('user_id', $user->id)->orderBy('booking_date', 'DESC')
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

                return response()->json(['success'=>false,'errors' =>['exception' => ['No bookings found']]], $this->successStatus);
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
