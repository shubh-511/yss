<?php

namespace App\Listeners;

use App\Events\FailedBookingEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Booking;
use Mail;

class FailedBookingListner
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
     * @param  FailedBookingEvent  $event
     * @return void
     */
    public function handle(FailedBookingEvent $event)
    {
        $user = User::find($event->userDetail)->toArray();
        $booking = Booking::find($event->bookingDetail)->toArray();

        Mail::send('emails.failed_booking', ["user"=>$user, "booking"=>$booking], function($message) use ($user) {
            $message->from('no-reply@yss.com');
            $message->to($user['email']);
            $message->subject('Payment Failed');
        });
    }
}
