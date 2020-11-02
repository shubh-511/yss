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
	            'slot' => 'required', 
	            'booking_date' => 'required',
                'token' => 'required',
                'card_id' => 'required', 
	        ]);

			if ($validator->fails()) 
            { 
	            return response()->json(['errors'=>$validator->errors()], $this->successStatus);       
			}
            $user = Auth::user();
             
            //$user = $request->user_id;

            $packageAmt = Package::where('id', $request->package_id)->first();

            $connectedActID = StripeConnect::where('user_id', $request->counsellor_id)->first();

            Stripe\Stripe::setApiKey('sk_test_51HeJy8FLGFzxhmLyc7WD0MjMrLNiXexvbyiYelajGk7OZF8Mvh3y2NUWEIX2XuTfQG2txpl3N38yYSva0qqz7lkj00qOEAhKE9');
            
            $stripe = new Stripe\StripeClient('sk_test_51HeJy8FLGFzxhmLyc7WD0MjMrLNiXexvbyiYelajGk7OZF8Mvh3y2NUWEIX2XuTfQG2txpl3N38yYSva0qqz7lkj00qOEAhKE9');

            /*Stripe\Stripe::setApiKey('sk_test_4QAdALiSUXZHzF1luppxZbsW00oaSZCQnZ');
            $stripe = new \Stripe\StripeClient('sk_test_4QAdALiSUXZHzF1luppxZbsW00oaSZCQnZ');*/


            $customer = \Stripe\Customer::create(array(
                'name' => 'shubh',
                'email' => 'shubh@gmail.com',
            ));
           
            /*$token = $stripe->tokens->create([
              'card' => [
                'number' => '4242424242424242',
                'exp_month' => 11,
                'exp_year' => 2021,
                'cvc' => '314',
              ],
            ]);*/
          

            $source = \Stripe\Customer::createSource(
            $customer->id,
            ['source' => $request->token]);

            //return $source;

            $payment_intent = \Stripe\PaymentIntent::create([
              'payment_method_types' => ['card'],
              'amount' => $packageAmt->amount*100,
              'description' => 'test payment',
              'customer' => $customer->id,
              'currency' => 'GBP',
              'source' => $request->card_id, 
              //'application_fee_amount' => 50,
              'transfer_data' => [
                'amount' => 50*100,
                'destination' => $connectedActID->stripe_id
              ],
            ]);

            $conf = $stripe->paymentIntents->confirm(
              $payment_intent->id,
              ['payment_method' => 'pm_card_visa_debit']
            );
            
           
            if($conf->status == 'succeeded')
            {

                $booking = new Booking; 
                //$booking->user_id = $user;
                $booking->counsellor_id = $request->counsellor_id;
                $booking->slot = $request->slot;
                $booking->booking_date = $request->booking_date;
                $booking->package_id = $request->package_id;
                $booking->status = 1;
                $booking->save();


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
            $user = Auth::user()->id;
            
            $allBookings = Booking::where('counsellor_id', $user)->get(); 
            if(count($allBookings) > 0)
            {
                $pastBookings = Booking::with('counsellor','package','user')->where('counsellor_id', $user)->where('booking_date', '<', Carbon::today())->get();
                $todaysBooking = Booking::with('counsellor','package','user')->where('counsellor_id', $user)->where('booking_date', Carbon::today())->get();
                $upcomingBooking = Booking::with('counsellor','package','user')->where('counsellor_id', $user)->where('booking_date', '>', Carbon::today())->get();

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
