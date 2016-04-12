<?php

namespace App\Events;

use App\Events\Event;
use App\Models\Subscriber;

class ChangeSubscriberGroup extends Event
{
    /**
     * @var \App\Models\Subscriber
     */
    public $subscriber;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Subscriber $subscriber
     */
    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
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
