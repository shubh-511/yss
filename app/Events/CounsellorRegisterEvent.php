<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CounsellorRegisterEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $userId;
    public $userPassword;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userId, $userPassword)
    {
        $this->userId = $userId;
        $this->userPassword = $userPassword;
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
