<?php

namespace App\Listeners;

use App\Events\CounsellorRegisterEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Booking;
use Mail;

class CounsellorRegisterListner
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
     * @param  CounsellorRegisterEvent  $event
     * @return void
     */
    public function handle(CounsellorRegisterEvent $event)
    {
        $user = User::find($event->userId)->toArray();
        $userPassword = $event->userPassword;
        // Mail::send('emails.counsellor_register_by_admin', ["user"=>$user, "userPassword"=>$userPassword], function($message) use ($user, $userPassword) {
        //     $message->from(env('MAIL_FROM_ADDRESS'));
        //     $message->to($user['email']);
        //     $message->subject('Welcome to Soberlistic');
        // });
    }
}
