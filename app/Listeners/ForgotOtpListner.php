<?php

namespace App\Listeners;

use App\Events\ForgotPasswordEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Mail;

class ForgotOtpListner
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
     * @param  ForgotPasswordEvent  $event
     * @return void
     */
    public function handle(ForgotPasswordEvent $event)
    {
        $user = User::find($event->userDetail)->toArray();
        Mail::send('emails.forgot_otp', ["user"=>$user], function($message) use ($user) {
            $message->to($user['email']);
            $message->subject('Forgot Password');
        });
    }
}
