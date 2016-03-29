<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
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
        $xml = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><FromUserName><![CDATA[FromUser]]></FromUserName><CreateTime>123456789</CreateTime><MsgType><![CDATA[event]]></MsgType><Event><![CDATA[subscribe]]></Event></xml>";
        $this->action('POST', 'MainController@postIndex', [], [], [], [], [], $xml);
        $this->assertResponseOk();
    }
}
