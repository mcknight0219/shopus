<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use App\Events\ChangeSubscriberGroup as Event;

/**
 * Change the vendor's to its own group so that
 * they can see customized menu.
 *
 * @package App\Listeners
 */
class MoveSubscriberGroup
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
     * @param  \App\Events\ChangeSubscriberGroup  $event
     * @return void
     */
    public function handle(Event $event)
    {
        try {
            app()->make('Api')->request('POST', 'groups/members/update', [
                'json' => [
                    'openid'    => $event->subscriber->openId,
                    'to_groupid'=> DB::table('groups')->select('id')->where('name', 'vendor')->first()->id
                ]
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

    }
}
