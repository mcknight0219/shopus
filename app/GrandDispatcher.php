<?php

namespace App;

use Event;
use App\Models\Message;
use Illuminate\Support\Str;

class GrandDispatcher 
{
    /**
     * @var
     */
    protected $response = null;

    /**
     * All messages will go through this dispatcher in order to be processed
     *
     * @param \App\Models\Message $msg
     */
	public function dispatch(Message $msg)
	{
		if ($msg->messageable->unique()) {
            call_user_func([$this, $this->translateToMethod(get_class($msg->messageable))], $msg);
        }

        return $this;
	}

    /**
     * Map the message class to instance method that handles it
     *
     * @param str $klass
     * @return str
     */
    protected function translateToMethod($klass)
    {
        return end((explode('\\', Str::lower($klass))));
    }

    /**
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function getResponse()
    {
        if ($this->response instanceof Message) {
            return $this->response->toXml();
        } 
        return $this->response ?: 'success'; 
    }

    /**
     * Fire up Event to handle event messages
     *
     * @param \App\Models\Message $msg
     */
	protected function event($msg)
    {
        $eventName = collect([
            'subscribe'     => 'App\Events\WechatUserSubscribed',
            'unsubscribe'   => 'App\Events\WechatUserUnsubscribed',
            'scan'          => 'App\Events\WechatScanned'
        ])->get(Str::lower($msg->messageable->event));
        
        Event::fire(new $eventName($msg));
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
     * After event is handled, a response can be sent back to weixin side
     *
     * @param mixed $msg
     */
    protected function setRespondMessage($msg)
    {
        $this->response = $msg;
    }
}
