<?php

namespace App\Listeners;

use App\Profile;
use App\MessageFactory;
use App\Models\Subscriber;
use Illuminate\Support\Str;
use App\Events\WechatUserSubscribed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribeUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\WechatUserSubscribed  $event
     */
    public function handle(WechatUserSubscribed $event)
    {
        $m = $event->message;
        $subscriber = new Subscriber;
        $subscriber->openId = $m->fromUserName;
        $subscriber->save();
        if (! is_null($m->messageable->eventKey)) {
            // scene id is profile id so we can link subscriber with user (a.k.a vendor)
            $sceneId = Str::substr($m->messageable->eventKey, Str::length('qrscene_'));
            $profile = Profile::find($sceneId);
            $profile->weixin = $m->fromUserName;
        }

        // Return the text message to welcome user
        return with(new MessageFactory)->create(
            [
                'FromUserName'  => env('WECHAT_ACCOUNT'),
                'ToUserName'    => $m->toUserName,
                'CreateTime'    => time(),
                'MsgType'       => 'text',
                'Content'       => 'Weclome to our official account !'
            ],
            'outbound'
        );
    }
}
