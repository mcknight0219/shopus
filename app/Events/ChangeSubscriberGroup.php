<?php

namespace App\Events;

use App\Events\Event;

class ChangeSubscriberGroup extends Event
{
    /**
     * @var array
     */
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($openId, $toGroupId)
    {
        $this->data = [
            'openId'    => $openId,
            'toGroupId' => $toGroupId
        ];
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
