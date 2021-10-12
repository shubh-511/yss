<?php

namespace App\Listeners;

use App\Events\CounsellorLeftSessionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        //
    }
}
