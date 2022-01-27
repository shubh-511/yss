<?php

namespace App\Listeners;

use App\Events\CancelBookingEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Mail;

class CancelBookingListner
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
     * @param  CancelBookingEvent  $event
     * @return void
     */
    public function handle(CancelBookingEvent $event)
    {
        $user = User::find($event->userId)->toArray();
        Mail::send('emails.cancel_booking_counsellor', ["user"=>$user], function($message) use ($user) {
            //$message->from(env('MAIL_FROM_ADDRESS'));
            $message->to($user['email']);
            $message->subject('Soberlistic');
        });
    }
}
