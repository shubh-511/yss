<?php

namespace App\Listeners;

use App\Events\ApproveListingEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\User;

class ApproveListingListner
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
     * @param  ApproveListingEvent  $event
     * @return void
     */
    public function handle(ApproveListingEvent $event)
    {
        $user = User::find($event->user_id)->toArray(); 
        Mail::send('emails.approve_listing', ["user"=>$user], function($message) use ($user) {
            //$message->from('support@soberlistic.com');
            $message->to($user['email']);
            $message->subject('Soberlistic');
        });
    }
}
