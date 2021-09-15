<?php

namespace App\Listeners;

use App\Events\BookingCounsellorEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Booking;
use Mail;

class BookingCounsellorListner
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
     * @param  BookingCounsellorEvent  $event
     * @return void
     */
    public function handle(BookingCounsellorEvent $event)
    {
        $user = User::find($event->userDetail)->toArray();
        $bookedUser = User::find($event->bookedUser)->toArray();
        $booking = Booking::find($event->bookingDetail)->toArray();

        Mail::send('emails.success_booking_counsellor', ["user"=>$user, "booking"=>$booking, "bookedUser"=>$bookedUser], function($message) use ($user) {
            $message->from(env('MAIL_FROM_ADDRESS'));
            $message->to($user['email']);
            $message->subject('New Booking');
        });
    }
}
