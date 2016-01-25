<?php

namespace App;

use DB;
use Log;
use App\Models\Message;
use App\Action;

use App\Models\Subscriber;

// A central place to handle received message 
class GrandDispatcher 
{
	public function handle(Message $msg)
	{
		// If message is redundant, we do nothing
		if( $this->_isOutbound($msg) || $this->_isRedundant($msg) ) {
			return Action::Noop;
		}
	
		if( $msg->msgType === 'event' ) return $this->_handleEventMessage($msg);
		else return $this->_handleIncomingMessage($msg);
	}
	
	protected function _handleEventMessage($msg)
	{
		$event = $msg->event;
		if( $event === 'unsubscribe' ) {
			$id = $msg->fromUserName;
			$user = Subscriber::where('openId', $id)->get();
			if( $user === null ) {
				Log::warning("Subscriber ${id}'s record is missing from table subscribers");
			} else {
				$user->unsubscribed = true;
				$user->save();
			}
			return Action::Noop;
		} else if( $event === 'subscribe' ) {
			$user = Subscriber(['unionId' => $msg->fromUserName]);
			$user->save();
			$action = new Action();
			$action->welcome($user);	
		} else if( $event === 'CLICK' ) {

		} else if( $event === 'VIEW') {

		} else if( $event === 'SCAN' ) {
			return Action::Noop;
		}
	}
	
	protected function _handleIncomingMessage($msg)
	{
		
	}
	
	protected function _isOutbound(Message $msg)
	{
		return get_class($msg->messageable) === 'Outbound';
	}
	
	protected function _isRedundant(Message $msg)
	{
		$klass = get_class($msg->messageable());
		// Inbound message is distinquished by msgId.
		// Event message can be differed by FromUserName + CreateTime
		if( $klass === 'Inbound' ) {
			$result = Inbound::where('msgId',$msg->messageable->msgId)->get();
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