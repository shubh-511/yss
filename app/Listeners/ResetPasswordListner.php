<?php

namespace App\Listeners;

use App\Events\ResetPasswordEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Booking;
use Mail;

class ResetPasswordListner
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
     * @param  ResetPasswordEvent  $event
     * @return void
     */
    public function handle(ResetPasswordEvent $event)
    {
        $user = User::find($event->userDetail)->toArray();

        /*Mail::send('emails.reset_password', ["user"=>$user], function($message) use ($user) {
            $message->from(env('MAIL_FROM_ADDRESS'));
            $message->to($user['email']);
            $message->subject('Password Reset');
        });*/
    }
}
