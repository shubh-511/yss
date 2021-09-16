<?php

namespace App\Listeners;

use App\Events\ContactEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Contact;

class ContactListner
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
     * @param  ContactEvent  $event
     * @return void
     */
    public function handle(ContactEvent $event)
    {
        $user = Contact::find($event->userId)->toArray();
        // Mail::send('emails.contact', ["user"=>$user,"msg"=>"test"], function($message) use ($user) {
        //     $message->from($user['email']);
        //     $message->to('ashishibyte@gmail.com');
        //     $message->subject('Soberlistic');
        // });
    }
}
