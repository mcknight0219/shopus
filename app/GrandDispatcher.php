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
     * @return \App\GrandDispatcher
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
     * @param str $class
     * @return str
     */
    protected function translateToMethod($class)
    {
        return end((explode('\\', Str::lower($class))));
    }

    /**
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function getResponse()
    {
        if ($this->response instanceof Message) {
            return response()->make($this->response->messageable->toXml(), 200, ['Content-Type' => 'application/xml']);
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
        $ev = collect([
            'subscribe'     => 'App\Events\WechatUserSubscribed',
            'unsubscribe'   => 'App\Events\WechatUserUnsubscribed',
            'scan'          => 'App\Events\WechatScanned'
        ])->get(Str::lower($msg->messageable->event));
        
        $this->setResponse(event(new $ev($msg)));
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
     * Set the response of the event 
     *
     * @param mixed $msg
     */
    protected function setResponse($msg)
    {
        $this->response = is_array($msg) ? $msg[0] : (string)$msg;
    }
}
