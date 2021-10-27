<?php

namespace App\Listeners;

use App\Events\BookLeftSession;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\User;

class BookLeftSessionListner
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
     * @param  BookLeftSession  $event
     * @return void
     */
    public function handle(BookLeftSession $event)
    {
         $user = User::find($event->user_id)->toArray(); 
         /*Mail::send('emails.leftsession', ["user"=>$user,"left_session_val"=>$event->left_session_val,"myslots"=>$event->myslots], function($message) use ($user) {
            $message->from(env('MAIL_FROM_ADDRESS'));
            $message->to($user['email']);
            $message->subject('Soberlistic');
        });*/
    }
}
