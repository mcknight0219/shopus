<?php

namespace App;

use DB;
use Log;
use App\Models\Message;
use App\Models\Actions\NoopAction;
use App\Models\Actions\SubscribeAction;

// A central place to handle received message 
class GrandDispatcher 
{
	public function handle(Message $msg)
	{
		// If message is redundant, we do nothing
		if( $this->_checkRedundant($msg) ) {
			return new NoopAction;
		}
	
		if( $msg->msgType === 'event' ) {
            return $this->handleEventMessage($msg);
        } else {
            return $this->handleIncomingMessage($msg);
        }
	}
	
	protected function handleEventMessage($msg)
	{
        switch ($msg->messageable->event) {
            case 'subscribe':
                $action = new SubscribeAction; break;
            case 'unsubscribe':
                $action = new UnsubscribeAction; break;
            case 'click':
                $action = new MenuClickAction; break;
            case 'VIEW':
                $action = new MenuViewAction; break;
            case 'SCAN':
            case 'LOCATION':
                return new NoopAction;
        }

        $action->setMessage($msg);
        return $action;
	}
	
	protected function handleIncomingMessage($msg)
	{
		
	}

	protected function _checkRedundant(Message $msg)
	{
		$klass = get_class($msg->messageable);
		// Inbound message is distinguished by msgId.
		// Event message can be differed by FromUserName + CreateTime
		if( $klass === 'Inbound' ) {
			$result = Inbound::where('msgId', $msg->messageable->msgId)->get();
			return $result !== null;
		} else if( $klass === 'Event' ) {
			$result = Message::where('FromUserName', $msg->fromUserName)
				->where('CreateTime', $msg->createTime)
				->get();
			return $result !== null;
		} else {
			return false;
		}
	}
}