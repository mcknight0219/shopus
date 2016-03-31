<?php

use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscriptionTest extends TestCase
{
    use DatabaseTransactions;
    /**
     *  Test event of user subscribing to official event 
     *
     * @return void
     */
    public function testSubscribeToOfficialAccount()
    {
        $this->trigger("<xml><ToUserName><![CDATA[toUser]]></ToUserName><FromUserName><![CDATA[FromUser]]></FromUserName><CreateTime>123456789</CreateTime><MsgType><![CDATA[event]]></MsgType><Event><![CDATA[subscribe]]></Event></xml>");
        $this->assertResponseOk();
        $this->see('success');

        $subscriber = Subscriber::where('openId', 'FromUser')->first();
        $this->assertNotNull($subscriber);
    }

    public function testUnsubscribeOfficialAccount()
    {
        $this->trigger("<xml><ToUserName><![CDATA[toUser]]></ToUserName><FromUserName><![CDATA[FromUser]]></FromUserName><CreateTime>123456789</CreateTime><MsgType><![CDATA[event]]></MsgType><Event><![CDATA[unsubscribe]]></Event></xml>");
        factory('App\Models\Subscriber')->create(); 
        
    }

    protected function trigger($xml)
    {
        $this->action('POST', 'MainController@postIndex', [], [], [], [], [], $xml);
    }
}
