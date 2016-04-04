<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CategoryFound extends Event
{
    use SerializesModels;

    /**
     * @var integer
     */
    public $category;
    /**
     * Create a new event instance.
     *
     * @param array $payload
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
