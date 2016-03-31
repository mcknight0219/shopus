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
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  WechatUserUnsubscribed  $event
     * @return void
     */
    public function handle(WechatUserUnsubscribed $event)
    {
        Subscriber::where('openId', $event->fromUserName)->first()->unsubscribed = true;
    }
}
