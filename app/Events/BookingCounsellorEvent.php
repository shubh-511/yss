<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BookingCounsellorEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $userDetail;
    public $bookingDetail;
    public $bookedUser;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($bookingDetail, $userDetail, $bookedUser)
    {
        $this->bookingDetail = $bookingDetail;
        $this->userDetail = $userDetail;
        $this->bookedUser = $bookedUser;
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
