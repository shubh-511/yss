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

            Stripe\Stripe::setApiKey('sk_live_ZnJs1EudLzYjghd5zGm3WAkY00jT2Q2d1U');
            
            $stripe = new Stripe\StripeClient('sk_live_ZnJs1EudLzYjghd5zGm3WAkY00jT2Q2d1U');

            /*Stripe\Stripe::setApiKey('sk_test_4QAdALiSUXZHzF1luppxZbsW00oaSZCQnZ');
            $stripe = new \Stripe\StripeClient('sk_test_4QAdALiSUXZHzF1luppxZbsW00oaSZCQnZ');*/


            $customer = \Stripe\Customer::create(array(
                'name' => $user->name,
                'email' => $user->email,
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
              //'amount' => $packageAmt->amount*100,
              'amount' => 10,
              'description' => 'test payment',
              'customer' => $customer->id,
              'currency' => 'INR',
              'source' => $request->card_id, 
              //'application_fee_amount' => 50,
              'transfer_data' => [
                'amount' => 3,
                'destination' => $connectedActID->stripe_id
              ],
            ]);

            $conf = $stripe->paymentIntents->confirm(
              $payment_intent->id,
              ['payment_method' => 'pm_card_visa_debit']
            );
            
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
            $payment->source = $conf->charges->data[0]->source;
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
                $booking->user_id = $user->id;
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
            $user = Auth::user();
            if($user->role_id == 2)
            {
                $allBookings = Booking::where('counsellor_id', $user->id)->get(); 
            }
            else
            {
                $allBookings = Booking::where('user_id', $user->id)->get(); 
            }
            
            if(count($allBookings) > 0)
            {
                $pastBookings = Booking::with('counsellor','package','user')->where('counsellor_id', $user->id)->where('booking_date', '<', Carbon::today())->get();
                $todaysBooking = Booking::with('counsellor','package','user')->where('counsellor_id', $user->id)->where('booking_date', Carbon::today())->get();
                $upcomingBooking = Booking::with('counsellor','package','user')->where('counsellor_id', $user->id)->where('booking_date', '>', Carbon::today())->get();

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
