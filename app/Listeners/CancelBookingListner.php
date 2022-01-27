<?php

namespace App\Listeners;

use App\Events\CancelBookingEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelBookingListner
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
     * @param  CancelBookingEvent  $event
     * @return void
     */
    public function handle(CancelBookingEvent $event)
    {
        //
    }
}
