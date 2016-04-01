<?php

namespace App\Events;

use App\Events\Event;
use App\Models\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WechatUserUnsubscribed extends Event
{
    use SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
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
