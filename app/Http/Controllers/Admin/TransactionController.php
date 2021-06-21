<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Booking;
use Illuminate\Support\Facades\Auth; 
use PDF;
use Carbon\Carbon;
use App\Package;
use App\Payment;
use App\User; 
use DB;

class TransactionController extends Controller
{
	   public $successStatus = 200;
	    public function transactionlist(Request $request)
	    {
	    	$counsellor_data= User::where('role_id','2')->get();
	    	$payment_data= Payment::get();

	              $bookings = Booking::with('payment_detail')->where(function ($query) use ($request)
	                {
                       if ($request->get('counsellor') != null) 
                       { 
						 $query->where('counsellor_id','=',$request->get('counsellor'));
						}
						if ($request->get('date') != null) 
                        { 
						 $query->where('booking_date','>=',Carbon::now()->subDays($request->get('date')));
						}

		                if ($request->get('counsellor') != null && $request->get('date') != null) 
		                {
			             $query->where('counsellor_id','=',$request->counsellor)->where('booking_date','>=',Carbon::now()->subDays($request->get('date')));
			            }
                   })->whereNotIn('payment_id',['0'])->orderBy('id','DESC')->paginate(25);
	               return view('admin.transaction.index',compact('bookings','counsellor_data','payment_data'))
	            ->with('i', ($request->input('page', 1) - 1) * 5);
	    }
	    public function download(Request $request)
	    {
	      try
	       {
		      $counsellor=$request->counsellor;
		      $date=$request->date;
		      $date_format=Carbon::now()->subDays($date);
		      if($counsellor != "" && $date !="")
		       {
			      $booking = Booking::with('payment_detail','counsellor','user','package')->where('counsellor_id', $counsellor)->where('booking_date','>=',$date_format->format('Y-m-d'))->get();
			       $data["bookingData"] = $booking;
		           $pdf = PDF::loadView('admin.transaction.revenuereport',$data);
		            return $pdf->download('revenuereport.pdf');
		        }
		        else if($counsellor != "")
		        {
		        	 $booking = Booking::with('payment_detail','counsellor','user','package')->where('counsellor_id', $counsellor)->get();
			       $data["bookingData"] = $booking;
		           $pdf = PDF::loadView('admin.transaction.revenuereport',$data);
		            return $pdf->download('revenuereport.pdf');
		        }
		        else if($date !="")
		       {
			      $booking = Booking::with('payment_detail','counsellor','user','package')->where('booking_date','>=',$date_format->format('Y-m-d'))->get();
			       $data["bookingData"] = $booking;
		           $pdf = PDF::loadView('admin.transaction.revenuereport',$data);
		            return $pdf->download('revenuereport.pdf');
		        }
		        else
		        {

		        }

            }
		     catch(\Exception $e)
		        {
		          return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
		        } 
	    }
}
