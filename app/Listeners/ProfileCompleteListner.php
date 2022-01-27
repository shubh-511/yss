<?php

namespace App\Listeners;

use App\Events\ProfileCompleteEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Booking;
use Mail;

class ProfileCompleteListner
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
     * @param  ProfileCompleteEvent  $event
     * @return void
     */
    public function handle(ProfileCompleteEvent $event)
    {
        $user = User::find($event->userDetail)->toArray();
        
        Mail::send('emails.profile_completion', ["user"=>$user], function($message) use ($user) {
            //$message->from(env('MAIL_FROM_ADDRESS'));
            $message->to($user['email']);
            $message->subject('Profile Completed');
        });
    }
}
