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
            //$user = User::where('id', $request->user_id)->first();
            //$user = $request->user_id;

            $packageAmt = Package::where('id', $request->package_id)->first();

            $connectedActID = StripeConnect::where('user_id', $request->counsellor_id)->first();

            Stripe\Stripe::setApiKey('sk_live_ZnJs1EudLzYjghd5zGm3WAkY00jT2Q2d1U');
            
            $stripe = new Stripe\StripeClient('sk_live_ZnJs1EudLzYjghd5zGm3WAkY00jT2Q2d1U');

            /*Stripe\Stripe::setApiKey('sk_test_4QAdALiSUXZHzF1luppxZbsW00oaSZCQnZ');
            $stripe = new \Stripe\StripeClient('sk_test_4QAdALiSUXZHzF1luppxZbsW00oaSZCQnZ');*/


             /*$token = $stripe->tokens->create([
              'card' => [
                'number' => '5126522005865259',
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
                'name' => 'shubham',
                'email' => 'shubham12@gmail.com',
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

            

            $payment_intent = \Stripe\PaymentIntent::create([
              //'amount' => $packageAmt->amount*100,
              'amount' => 3*100,
              'description' => 'test payment',
              'customer' => $customer->id,
              'currency' => 'INR',
              //'source' => $token->card->id, 
              'source' => $request->card_id, 
              'confirmation_method' => 'manual',
              'confirm' => true,
              //'application_fee_amount' => 50,
              'transfer_data' => [
                'amount' => 1*100,
                'destination' => $connectedActID->stripe_id
              ],
            ]);

            //return $payment_intent;



            $conf = $stripe->paymentIntents->confirm(
              $payment_intent->id,
              ['return_url' => 'http://178.62.24.141/dev/api/hook/callback?payment_intent='.$payment_intent->id.'&counsellor_id='.$request->counsellor_id.'&slot='.$request->slot.'&booking_date='.$request->booking_date.'&package_id='.$request->package_id.'&user='.$user->id.'&notes='.$request->notes]
              //['payment_method' => $method->id]
            );
            //return $conf;

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

            Stripe\Stripe::setApiKey('sk_live_ZnJs1EudLzYjghd5zGm3WAkY00jT2Q2d1U');
            
            $stripe = new Stripe\StripeClient('sk_live_ZnJs1EudLzYjghd5zGm3WAkY00jT2Q2d1U');

            /*$conf = $stripe->paymentIntents->retrieve(
              $request->payment_intent,
              []
            );*/

            $conf = $stripe->paymentIntents->confirm(
              $request->payment_intent,
              []
            );
            
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
            $user = Auth::user();
            if($user->role_id == 2)
            {
                $allBookings = Booking::where('counsellor_id', $user->id)->get(); 

                if(count($allBookings) > 0)
                { 
                    $pastBookings = Booking::with('counsellor','package','user')
                    ->where('counsellor_id', $user->id)
                    ->where('booking_date', '<', Carbon::today())
                    //->paginate(10);
                    ->get();

                    $todaysBooking = Booking::with('counsellor','package','user')
                    ->where('counsellor_id', $user->id)
                    ->where('booking_date', Carbon::today())
                    //->paginate(10);
                    ->get();

                    $upcomingBooking = Booking::with('counsellor','package','user')
                    ->where('counsellor_id', $user->id)
                    ->where('booking_date', '>', Carbon::today())
                    //->paginate(10);
                    ->get();

                    $currentWeekBooking = Booking::with('counsellor','package','user')->where('counsellor_id', $user->id)
                    ->where('booking_date', '>', Carbon::now()->startOfWeek())
                    ->where('booking_date', '<', Carbon::now()->endOfWeek())
                    //->paginate(10);
                    ->get();

                    return response()->json(['success' => true,
                                         'past' => $pastBookings,
                                         'todays' => $todaysBooking,
                                         'upcoming' => $upcomingBooking,
                                         'current_week' => $currentWeekBooking,
                                        ], $this->successStatus);
                }
                else
                {
                    return response()->json(['success' => false,
                                         'message' => 'No bookings found',
                                        ], $this->successStatus);
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
                    //->paginate(10);
                    ->get();

                    $todaysBooking = Booking::with('counsellor','package','user')
                    ->where('user_id', $user->id)
                    ->where('booking_date', Carbon::today())
                    //->paginate(10);
                    ->get();

                    $upcomingBooking = Booking::with('counsellor','package','user')
                    ->where('user_id', $user->id)
                    ->where('booking_date', '>', Carbon::today())
                    //->paginate(10);
                    ->get();

                    $currentWeekBooking = Booking::with('counsellor','package','user')->where('counsellor_id', $user->id)
                    ->where('created_at', '>', Carbon::now()->startOfWeek())
                    ->where('created_at', '<', Carbon::now()->endOfWeek())
                    //->paginate(10);
                    ->get();

                    return response()->json(['success' => true,
                                         'past' => $pastBookings,
                                         'todays' => $todaysBooking,
                                         'upcoming' => $upcomingBooking,
                                         'current_week' => $currentWeekBooking,
                                        ], $this->successStatus);
                }
                else
                {
                    return response()->json(['success' => false,
                                         'message' => 'No bookings found',
                                        ], $this->successStatus);
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
            ]);

            if ($validator->fails()) 
            { 
                return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
            }

            $user = Auth::user();
            
            $booking = Booking::where('id', $request->booking_id)->where('counsellor_id', $user->id)->first(); 
            
            if(!empty($booking))
            {
                $booking->status = '3'; 
                $booking->save();

                $bookingStatus = Booking::where('id', $request->booking_id)->where('counsellor_id', $user->id)->first(); 

                return response()->json(['success' => true,
                                         'message' => 'Booking confirmed!',
                                         'data'    => $bookingStatus,
                                        ], $this->successStatus);
            }
            else
            {
                return response()->json(['success' => false,
                                         'message' => 'No bookings found with this booking ID',
                                        ], $this->successStatus);
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
              $booking = Booking::with('package')->where('counsellor_id', $user->id)->orderBy('booking_date', 'DESC')
                ->withTrashed()
                ->get();
            }
            else
            {
              $booking = Booking::with('package')->where('user_id', $user->id)->orderBy('booking_date', 'DESC')
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
