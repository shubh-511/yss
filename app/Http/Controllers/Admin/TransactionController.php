<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Booking;
use App\User;
use Illuminate\Support\Facades\Auth; 
use PDF;
use Carbon\Carbon;
use App\Package; 

class TransactionController extends Controller
{
	    public function transactionlist(Request $request)
	    {
	       if ($request->get('name') != null && $request->get('date') != null) 
	        {

	          $bookings = Booking::with('user')->whereHas('counsellor', function ($query) use ($request)
	                {
		             $query->where('name','=',$request->name);
		             })->whereHas('user', function ($query) use ($request){
		             $query->where('booking_date','>=',Carbon::now()->subDays($request->get('date')));
		             })->orderBy('id','DESC')->paginate(25);
		            return view('admin.transaction.index',compact('bookings'));
	        }
	    	  $bookings=Booking::orderBy('id','DESC')->paginate(25);
	            return view('admin.transaction.index',compact('bookings'))
	            ->with('i', ($request->input('page', 1) - 1) * 5);
	    }
	    public function download(Request $request)
	    {
	      try
	       {
		      $name=$request->name;
		      $date=$request->date;
		      $date_format=Carbon::now()->subDays($date);
		      $counsellor_data = User::where('name', $name)->first();
		      if($counsellor_data != "")
		       {
			      $booking = Booking::with('payment_detail','counsellor','user','package')->where('counsellor_id', $counsellor_data->id)->where('booking_date','>=',$date_format->format('Y-m-d'))->get();
			       $data["bookingData"] = $booking;
		           $pdf = PDF::loadView('admin.transaction.revenuereport',$data);
		            return $pdf->download('revenuereport.pdf');
		        }
            }
		     catch(\Exception $e)
		        {
		          return response()->json(['success'=>false,'errors' =>['exception' => [$e->getMessage()]]], $this->successStatus); 
		        } 
	    }
}
