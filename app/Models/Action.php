<?php
namespace App\Models;

use App\Models\Message;
use App\Exceptions\ActionException;

abstract class Action
{
    protected $message;

    public function setMessage(Message $msg)
    {
        if( $msg === null ) {
            throw new ActionException('Incoming message must not be null');
        }

        $this->message = $msg;
    }    	

    abstract public function execute();

    abstract public function getActionType();
}