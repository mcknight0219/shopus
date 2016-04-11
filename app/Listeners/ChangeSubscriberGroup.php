<?php

namespace App\Listeners;

use Log;
use App\Events\ChangeSubscriberGroup;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChangeSubscriberGroup
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  ChangeSubscriberGroup  $event
     * @return void
     */
    public function handle(ChangeSubscriberGroup $event)
    {
        extract($event->data);
        try {
            app()->make('Api')->request('POST', 'groups/memebers/update', [
                'json' => [
                    'openid'    => $openId,
                    'to_groupid'=> $toGroupId
                ]
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

    }
}
