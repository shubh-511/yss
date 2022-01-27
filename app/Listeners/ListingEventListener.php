<?php

namespace App\Listeners;

use App\Events\ListingEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\User;
use App\Listing;

class ListingEventListener
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
     * @param  ListingEvent  $event
     * @return void
     */
    public function handle(ListingEvent $event)
    {
        $user = User::find($event->id)->toArray();
        $listing=Listing::where('user_id',$event->id)->first();
        
        Mail::send('emails.listing', ["user"=>$user,"listing"=>$listing], function($message) use ($user) {
            $message->from(env('MAIL_FROM_ADDRESS'));
            $message->to($user['email']);
            $message->subject('Soberlistic');
        });
    }
}
