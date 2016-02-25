<?php
namespace App;

use App\Models\Message;
use App\Models\Inbound;
use App\Models\Outbound;
use App\Models\Event;

use App\Exceptions\MessageFactoryException;

class MessageFactory
{
    public function create($type, $attributes)
    {
        $this->_guardParam($attributes);
        if( 'inbound' === $type ) {
            return $this->_createInbound($attributes);
        } 
        else if( 'outbound' === $type ) {
            return $this->_createOutbound($attributes);
        }
        else if( 'event' === $type ) {
            return $this->_createEvent($attributes);
        }
        else {
            throw new MessageFactoryException('Unknown message type');
        }
    }

    protected function _guardParam($params)
    {
        if( !is_array($params) || count($params) === 0 )
            throw new MessageFactoryException('Parameter is not array or is empty');
    }

    // common fields for all type of messages
    protected $_commonAttrs = ['ToUserName', 'FromUserName', 'CreateTime', 'MsgType'];

    protected function _createBasic($attributes)
    {
        try {
            $message = new Message;
            $message->toUserName = $attributes['ToUserName'];
            $message->fromUserName = $attributes['FromUserName'];
            $message->createTime = $this->_epochToTimestamp($attributes['CreateTime']);
            $message->msgType = $attributes['MsgType'];
            
            $message->save();
            return $message;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }   
    }

    // Message sent to us by normal subscribers
    protected function _createInbound($attributes)
    {   
        $message = $this->_createBasic($attributes);

        $inbound = new Inbound;
        $inbound->msgId = $attributes['MsgId'];
        $inbound->content = json_encode(
            array_filter($attributes, function($k) {
                return !in_array($k, array_merge($this->_commonAttrs, ['MsgId']));
            }, ARRAY_FILTER_USE_KEY)
        );
        $inbound->save();

        $inbound->message()->save($message);
        return $message;
    }

    // Response we sent to subscribers' message
    //
    // Outbound message is more complex than inbound and event.
    // we leave the burden of parsing to outbound model 
    protected function _createOutbound($attributes)
    {
        $message = $this->_createBasic($attributes);

        $outbound = new Outbound;
        $outbound->content = json_encode(
            array_filter($attributes, function($k) {
                return !in_array($k, $this->_commonAttrs);
            }, ARRAY_FILTER_USE_KEY)
        );
        $outbound->save();

        $outbound->message()->save($message);
        return $message;
    }

    // When events such as subscribing, menu-clicking happen, we
    // receive message from weixin
    protected function _createEvent(Array $attributes)
    {
        $message = $this->_createBasic($attributes);

        $event = new Event;
        $event->event = strtolower($attributes['Event']);
        $event->eventKey = array_key_exists('EventKey', $attributes) ? $attributes['EventKey'] : null;
        $event->ticket = array_key_exists('Ticket', $attributes) ? $attributes['Ticket'] : null;
        // For LOCATION event, pack Latitude, Longitude, and Precision in eventKey
        if (array_key_exists('Latitude', $attributes)) {
            $event->eventKey = array($attributes['Latitude'], $attributes['Longitude'], $attributes['Precision']).implode(';');
        }
        $event->save();
    
        $event->message()->save($message);
        return $message;
    }

    protected function _epochToTimestamp($epoch)
    {
        return date('Y-m-d H:i:s', intval($epoch));
    }
}