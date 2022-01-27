<?php

namespace App\Listeners;

use App\Events\CounsellorLeftSessionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\User;

class CounsellorLeftSessionListner
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
     * @param  CounsellorLeftSessionEvent  $event
     * @return void
     */
    public function handle(CounsellorLeftSessionEvent $event)
    {
          $user = User::find($event->counsellor_id)->toArray(); 
          Mail::send('emails.counsellor_left_session', ["user"=>$user,"left_session_val"=>$event->left_session_val,"myslots"=>$event->myslots], function($message) use ($user) {
           // $message->from(env('MAIL_FROM_ADDRESS'));
            $message->to($user['email']);
            $message->subject('Soberlistic');
        });
    }
}
