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
        $isVendor = false;
        if ($key = $m->messageable->eventKey) {
            // scene id is profile id so we can link subscriber with user (a.k.a vendor)
            Profile::find(Str::substr($key, Str::length('qrscene_')))->update(['weixin' => $m->fromUserName]);
            $isVendor = true;
        }

        // If we have already had group created, then fire the event to 
        // move subscriber to the group he belongs.
        $id = $this->groupId($isVendor) and event(new ChangeSubscriberGroup($subscriber->openId, $id)): ;
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

    /**
     * Determine the group id for vendor / non-vendor
     * 
     * @param  bool     $vendor 
     * @return mixed  
     */
    protected function groupId($vendor)
    {
        if (Cache::has('groupid_for_vendor') && Cache::has('groupid_for_nonvendor')) {
            return $vendor ? Cache::get('groupid_vendor') : Cache::get('groupid_nonvendor');
        }

        try {
            $filtered = collect(app()->make('Api')->request('GET', 'groups/get', [])->get('group'))->filter(function ($item) {
                return $item['name'] === ($vendor ? 'vendor' : 'non-vendor');
            });

            // No group has ever created
            if ($filtered->isEmpty()) {
                Log::info('Please run manage-group command to create groups.');
                $id = false;
            } else {
                $id = $filtered->first()['id'];
            }      
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $id = false;
        } finally {
            return $id;
        }
    }
}
