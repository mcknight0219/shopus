<?php

namespace App\Listeners;

use Str;
use App\Events\WechatScanned;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BackfillVendor
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
     * It's possible that vendor first subscribes to offical account in weixin
     * app or scans regular scene qr. and then scan the qr that 
     * are specially generated for their weixin id.
     *
     * @param  WechatScanned  $event
     * @return void
     */
    public function handle(WechatScanned $event)
    {
        $scene = Str::substr($event->message->messageable->eventKey, Str::length('qrscene_'));
        if ($scene !== 'regular') {
            Subscriber::where('openId', $event->message->fromUserName)->get()->weixinId = $scene;
        }        
    }
}
