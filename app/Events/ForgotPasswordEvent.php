<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ForgotPasswordEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $userDetail;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userDetail, $forgotKey)
    {
        $this->userDetail = $userDetail;
        $this->forgotKey = $forgotKey;
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
