<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CateogryFound extends Event
{
    use SerializesModels;

    /**
     * @var integer
     */
    public $cateory;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Array $payload)
    {
        $this->category = $payload['id'];
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
