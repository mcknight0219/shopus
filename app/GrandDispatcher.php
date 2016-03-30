<?php

namespace App;

use App\Models\Message;

class GrandDispatcher 
{
    /**
     * All messages will go through this dispatcher in order to be processed
     *
     * @param \App\Models\Message $msg
     */
	public function handle(Message $msg)
	{
		if ($msg->unique()) {
            call_user_func([$this, Str::lower(get_class($msg))], $msg);
		}
	}

    /**
     * Fire up Event to handle event messages
     *
     * @param \App\Models\Message $msg
     */
	protected function event($msg)
	{
    }

	/**
     * Fire up Event to handle user sent messages
     *
     * @param \App\Models\Message $msg
     */
	protected function inbound($msg)
	{
    }

    /**
     * Fire up Event to send out messages to users
     *
     * @param \App\Models\Message $msg
     */
    protected function outbound($msg)
    {
    }
}
