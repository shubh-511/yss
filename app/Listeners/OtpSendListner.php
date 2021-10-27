<?php

namespace App\Listeners;

use App\Events\UserRegisterEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Mail;

class OtpSendListner
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
     * @param  UserRegisterEvent  $event
     * @return void
     */
    public function handle(UserRegisterEvent $event)
    {
        $user = User::find($event->userId)->toArray();
        /*Mail::send('emails.otp', ["user"=>$user], function($message) use ($user) {
            $message->to($user['email']);
            $message->subject('OTP for registration');
        });*/
    }
}
