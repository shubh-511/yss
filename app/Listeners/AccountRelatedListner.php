<?php

namespace App\Listeners;

use App\Events\AccountRelatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\User;

class AccountRelatedListner
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
     * @param  AccountRelatedEvent  $event
     * @return void
     */
    public function handle(AccountRelatedEvent $event)
    {
        $user = User::find($event->id)->toArray();
        $user['subject'] = $event->subject;
        // Mail::send('emails.account_related', ["user"=>$user,"body"=>$event->body], function($message) use ($user) {
        //     $message->from(env('MAIL_FROM_ADDRESS'));
        //     $message->to($user['email']);
        //     $message->subject($user['subject']);
        // });
    }
}
