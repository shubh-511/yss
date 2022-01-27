<?php

namespace App\Listeners;

use App\Events\WelcomeUserEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Mail;

class WelcomeListner
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
     * @param  WelcomeUserEvent  $event
     * @return void
     */
    public function handle(WelcomeUserEvent $event)
    {
        $user = User::find($event->userDetail)->toArray();
        Mail::send('emails.welcome', ["user"=>$user], function($message) use ($user) {
            //$message->from(env('MAIL_FROM_ADDRESS'));
            $message->to($user['email']);
            $message->subject('Soberlistic');
        });
    }
}
