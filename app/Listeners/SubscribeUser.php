<?php

namespace App\Listeners;

use Cache;
use App\Profile;
use App\MessageFactory;
use App\Models\Subscriber;
use Illuminate\Support\Str;
use App\Events\WechatUserSubscribed;

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
     * @return \App\Models\Message
     */
    public function handle(WechatUserSubscribed $event)
    {
        $m = $event->message;
        /**
         * openId is always unique to our official account, so if user subscribes
         * again, we just need to toggle un-subscribed flag.
         */
        if($subscriber = Subscriber::where('openId', $m->fromUserName)->where('unsubscribed', true)->first()) {
            $subscriber->unsubscribed = false;
        } else {
            $subscriber = new Subscriber;
            $subscriber->openId = $m->fromUserName;
        }
        $subscriber->save();

        // Link profile with subscriber if subscribe comes from profile page.
        if ($key = $m->messageable->eventKey) {
            Profile::find(Str::substr($key, Str::length('qrscene_')))->update(['weixin' => $m->fromUserName]);
            event(new ChangeSubscriberGroup($subscriber));
        }
        return $this->greetMessage($m->fromUserName, !is_null($key));
    }

    /**
     * Greeting user after subscription.
     *
     * @param   string    $to
     * @param   bool      $isVendor
     * @return  \App\Models\Message
     */
    protected function greetMessage($to, $isVendor = false)
    {
        return with(new MessageFactory)->create(
            [
                'FromUserName'  => env('WECHAT_ACCOUNT'),
                'ToUserName'    => $to,
                'CreateTime'    => time(),
                'MsgType'       => 'text',
                'Content'       => $isVendor ? '欢迎加入Shopus! 开始分享你的商品吧' : '欢迎订阅我们的公众号 !'
            ],
            'outbound'
        );
    }
}
