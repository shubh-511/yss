<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\StripeConnect;
use Illuminate\Support\Facades\Auth; 
use App\User; 
use App\Booking;
use App\Payment;
use App\CallLog;
use Validator;
use Stripe;
use Carbon\Carbon;
use PDF;
use Mail;
use Event;
use App\Traits\ProfileStatusTrait;

class InvoiceController extends Controller
{
	use ProfileStatusTrait;
    public $successStatus = 200;
	

    /** 
     * Get Invoice
     *  
     * @return \Illuminate\Http\Response 
     */ 
    public function getInvoiceOverEmail($bookingId='') 
    {
    	try
    	{
    		$user = Auth::user();
    		$booking = Booking::with('payment_detail','counsellor','user','package')->where('id', $bookingId)->first();
    		if(!empty($booking))
			{
				$userTimezone = $booking->user->timezone;
				$currentUserTime = Carbon::now($userTimezone)->format('Y-m-d h:i A');
				$logs = CallLog::with('init_by','pick_by','cut_by','booking')->where('booking_id', $bookingId)->get();
				$data["currentUserTime"] = $currentUserTime;
				$data["logs"] = $logs;
	    		$data["bookingData"] = $booking;
	    		$data["email"] = "itsmeshubham511@gmail.com";
		        $data["title"] = "Invoice - Soberlistic";
		        $data["body"] = "Hey there, you just requested for invoice against your booking ID #".$booking->id.". Please find the attachment of your invoice.";
		  
		        $pdf = PDF::loadView('emails.getinvoicepdf', $data);
		  
		        Mail::send('emails.getinvoice', $data, function($message)use($data, $pdf) {
		            $message->to($data["email"], $data["email"])
		                    ->subject($data["title"])
		                    ->attachData($pdf->output(), "invoice.pdf");
		        });

		        return response()->json(['success' => true,
	            					 	'message'	=>	'Invoice has been sent on your email'
	            					], $this->successStatus);
		    }
		    else
		    {
		    	return response()->json(['success'=>false,'errors' =>['exception' => ['Invalid booking Id']]], $this->successStatus);
		    }
    	}
        catch(\Exception $e)
        {
    		return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
    	} 
        
    }

    

}