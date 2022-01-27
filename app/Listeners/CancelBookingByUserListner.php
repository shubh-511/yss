<?php

namespace App\Listeners;

use App\Events\CancelBookingByUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Mail;

class CancelBookingByUserListner
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
     * @param  CancelBookingByUser  $event
     * @return void
     */
    public function handle(CancelBookingByUser $event)
    {
        $user = User::find($event->userId)->toArray();
        Mail::send('emails.cancel_booking_user', ["user"=>$user], function($message) use ($user) {
            //$message->from(env('MAIL_FROM_ADDRESS'));
            $message->to($user['email']);
            $message->subject('Soberlistic');
        });
    }
}
