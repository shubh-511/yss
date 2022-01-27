<?php

namespace App\Listeners;

use App\Events\DisableListingEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\User;
use App\Listing;

class DisableListingListner
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
     * @param  DisableListingEvent  $event
     * @return void
     */
    public function handle(DisableListingEvent $event)
    {
         $user = User::find($event->id)->toArray();
        $listing=Listing::where('user_id',$event->id)->first();
        Mail::send('emails.disable_listing', ["user"=>$user,"listing"=>$listing], function($message) use ($user) {
            $message->from(env('MAIL_FROM_ADDRESS'));
            $message->to($user['email']);
            $message->subject('Soberlistic');
        });
    }
}
