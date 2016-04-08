<?php

namespace App\Listeners;

use App\Models\Subscriber;
use App\Events\WechatUserUnsubscribed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgetUser
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  WechatUserUnsubscribed  $event
     * @return void
     */
    public function handle(WechatUserUnsubscribed $event)
    {
        $subscriber = Subscriber::where('openId', intval($event->message->fromUserName))->first();
        $subscriber->unsubscribed = true;
        $subscriber->save();
    }
}
