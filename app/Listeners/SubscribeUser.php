<?php

namespace App\Listeners;

use App\Models\Subscriber;
use Illuminate\Support\Str;
use App\Events\WechatUserSubscribed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribeUser
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
     * @param  WechatUserSubscribed  $event
     * @return void
     */
    public function handle(WechatUserSubscribed $event)
    {
        $m = $event->message;
        $subscriber = new Subscriber;    
        $subscriber->openId = $m->fromUserName;
        if (! is_null($m->messageable->eventKey)) {
            $scene = Str::substr($m->messageable->eventKey, Str::length('qrscene_'));    
            if ($scene !== 'regular') {
                $subscriber->weixinId = $scene;
            }
        }

        $subscriber->save();
    }
}
