<?php

namespace App\Listeners;

use App\Events\RejectListingEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\User;
use App\Listing;

class RejectListingListner
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
     * @param  RejectListingEvent  $event
     * @return void
     */
    public function handle(RejectListingEvent $event)
    {
        $user = User::find($event->id)->toArray();
        $listing=Listing::where('user_id',$event->id)->first();
        Mail::send('emails.reject-listing', ["user"=>$user,"msg"=>$event->msg], function($message) use ($user) {
            $message->from(env('MAIL_FROM_ADDRESS'));
            $message->to($user['email']);
            $message->subject('Soberlistic');
        });
    }
}
