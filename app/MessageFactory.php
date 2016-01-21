<?php

namespace App;

use Message;
use Inbound;
use Outbound;
use Event;

use App\Exceptions\MessageFactoryException;

class MessageFactory
{
    public function create($type, $attributes)
    {
        switch $type {
            case 'inbound':
                return $this->_createInbound($attributes);
            case 'outbound':
                return $this->_createOutbound($attributes);
            case 'event':
                return $this->_createEvent($attributes);
            default:
                throw new MessageFactoryException('Unknown message type');
        }
    }

    protected function _createInbound($attributes)
    {   
        
    }

    protected function _createOutbound($attributes)
    {

    }

    protected function _createEvent($attributes)
    {

    }
}