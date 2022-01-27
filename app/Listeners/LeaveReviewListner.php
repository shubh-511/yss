<?php

namespace App\Listeners;

use App\Events\LeaveReviewEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Mail;

class LeaveReviewListner
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
     * @param  LeaveReviewEvent  $event
     * @return void
     */
    public function handle(LeaveReviewEvent $event)
    {
         $user = User::find($event->userId)->toArray();
        Mail::send('emails.profile_review', ["user"=>$user], function($message) use ($user) {
            $message->from(env('MAIL_FROM_ADDRESS'));
            $message->to($user['email']);
            $message->subject('Someone view your profile');
        });
    }
}
