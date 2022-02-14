<?php

namespace App\Listeners;

use App\Events\BookingCronCounsellorEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\User;
use Carbon\Carbon;
use App\Booking;

class BookingCronCounsellorListner
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BookingCronCounsellorEvent  $event
     * @return void
     */
    public function handle(BookingCronCounsellorEvent $event)
    {
        $users = User::find($event->userId)->toArray();
        $current_date=date('Y-m-d');
       foreach($users as $user)
       {
        $now = Carbon::now($user['timezone']);
        echo $now->format('h:i A');
        echo $user['timezone'];
        $booking = Booking::where('counsellor_booking_date',$current_date)->where('counsellor_timezone_slot',Carbon::now($user['timezone'])->addMinutes('30')->format('h:i A'))->count();

            if($booking>0){
             Mail::send('emails.reminder_counsellor', ["user"=>$user], function($message) use ($user) {
                $message->to($user['email']);
                $message->subject('Reminder mail');
            });
            }
       }

    }
}
