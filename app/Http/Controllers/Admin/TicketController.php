<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; 
use App\User; 
use App\UserTickets;
use App\Booking;
use App\Payment;
use App\Notification;
use Stripe;
use Validator;
use Event;
use DB;

class TicketController extends Controller
{
    public $successStatus = 200;
	
    /**
     * Display a listing of the tickets.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTickets(Request $request)
    {
      if ($request->get('name') != null && $request->get('status') != null && $request->get('date') != null) 
      {
       $tickets = UserTickets::with('user')->whereHas('user', function ($query) use ($request){
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        })->whereHas('user', function ($query) use ($request)
           {
         $query->where('status', 'LIKE', '%' . $request->get('status') . '%');
        })->where('created_at','like', '%' . date('Y-m-d', strtotime($request->get('date'))). '%')
        ->orderBy('id','DESC')->paginate(25);
        return view('admin.tickets.index',compact('tickets'));
      }

       if ($request->get('name') != null && $request->get('status') != null) 
      {
       $tickets = UserTickets::with('user')->whereHas('user', function ($query) use ($request){
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        })->whereHas('user', function ($query) use ($request)
           {
         $query->where('status', 'LIKE', '%' . $request->get('status') . '%');
        })->orderBy('id','DESC')->paginate(25);
        return view('admin.tickets.index',compact('tickets'));
      }
       if ($request->get('name') != null && $request->get('date') != null) 
      {
       $tickets = UserTickets::with('user')->whereHas('user', function ($query) use ($request){
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        })->where('created_at','like', '%' . date('Y-m-d', strtotime($request->get('date'))). '%')
       ->orderBy('id','DESC')->paginate(25);
        return view('admin.tickets.index',compact('tickets'));
      }

      if ($request->get('name') != null) 
      {
       $tickets = UserTickets::with('user')->whereHas('user', function ($query) use ($request){
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        })->orderBy('id','DESC')->paginate(25);
        return view('admin.tickets.index',compact('tickets'));
      }
        $tickets=UserTickets::where(function ($query) use($request) {
        if ($request->get('status') != null) { 
        $query->where('status',$request->get('status'));
        } 
        if ($request->get('date') != null) {
         $query->where('created_at','like', '%' . date('Y-m-d', strtotime($request->get('date'))). '%');
        }
        if ($request->get('date') != null && $request->get('status') != null)
       {
        $query->where('created_at', 'like', '%' . $request->get('date') . '%')
        ->where('status',$request->get('status'));
      }
      })->orderBy('id','DESC')->paginate(25);
        return view('admin.tickets.index',compact('tickets'));
           
    }
      
       public function active(Request $request)
    {
       $data=$request['action'];
       $id=$request['id'];
       if($data=="refund")
       {
        $user_data=UserTickets::whereIn('id', $id)
       ->update(['status' => '1']);
       return response()->json(array('message' => 'success'));
       }
        else if($data=="pending")
       {
        $user_data=UserTickets::whereIn('id', $id)
       ->update(['status' => '0']);
       return response()->json(array('message' => 'success'));
       }
       else
       {
         $user_data=\App\UserTickets::whereIn('id',$id)->delete();
          return response()->json(array('message' => 'success'));
       
       }
    }
    /**
     * Display a detail of the ticket.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTicketDetail($id='')
    {
        $ticket = UserTickets::with('user','booking')->where('id',$id)->first();
        return view('admin.tickets.detail',compact('ticket'));
    }

   /**
     * Refund Money.
     *
     */
    public function refundTicket($ticketId)
    {
        try
        {
            $stripe = new \Stripe\StripeClient(
            'sk_test_51IVk6mInEL6a47XwZYFPim5hOAN95WkN46LgAJHAMzu6FnnH1xPZ0C9HoK4xXRwtZiBWUrbX5OpKThxiO0HpmZsi001GW383pW'
            );

            $ticket = UserTickets::where('id', $ticketId)->first();
            if(!empty($ticket))
            {
                $refundObject = $stripe->refunds->create([
                  'charge' => $ticket->booking->payment_detail->charge_id,
                ]);

                if(!empty($refundObject))
                {
                    $ticket->status = '1';
                    $ticket->save();
                    //$booking = Booking::where('id', $ticket->booking_id)->update(['status' => '4']); //status changed to refund
                    $booking = Booking::with('payment_detail')->where('id', $ticket->booking_id)->first();
                    $paidAmount = $booking->payment_detail->amount;
                    Booking::where('payment_id', $booking->payment_id)->update(['status' => '4']);
                    Payment::where('id', $booking->payment_id)->update(['refunded' => '1', 'amount_refunded' => $paidAmount]);
                }

            }
            return redirect('login/tickets')->with('success','A Refund has been initiated against ticket ID '.$ticketId);
        }
        catch(\Exception $e)
        {
            return redirect('login/tickets')->with('success',$e->getMessage());
        }
    }

}