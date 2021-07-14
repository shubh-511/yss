<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RejectListingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $id;
    public $msg;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id,$msg)
    {
        $this->id = $id;
        $this->msg = $msg;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('channel-name');
        return []; 
    }
}
