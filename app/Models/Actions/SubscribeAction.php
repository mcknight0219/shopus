<?php
namespace App\Models\Actions;

use App\MessageFactory;
use Log;

use App\Models\Action;
use App\Models\Subscriber;

class SubscribeAction extends Action
{
    public function execute()
    {
        $user = Subscriber::where('openId', $this->message->fromUser)->get();
        if( $user === null ) {
            $user = Subscriber;
            $user->openId = $this->message->fromUserName;
        } else if( $user->unsubscribed ) {
            $user->unsubscribed = true;
        }

        try {
            $user->save();
        } catch(Exception $e) {
            Log::error('Cannot save subscriber ' . $this->message->fromUser);
        }

        $msg = (new MessageFactory)->create('outbound', [
            'FromUserName'  => '',
            'ToUserName'    => $this->message->fromUserName,
            'CreateTime'    => time(),
            'MsgType'       => 'text',
            'Content'       => ''
        ]);
        return $msg;
    }

    public function getActionType()
    {
        return 'subscribe';
    }
}