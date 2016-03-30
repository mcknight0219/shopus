<?php

namespace App\Listeners;

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
        //
    }
}
