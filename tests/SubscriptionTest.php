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
        $this->trigger("<xml><ToUserName><![CDATA[toUser]]></ToUserName><FromUserName><![CDATA[1234567]]></FromUserName><CreateTime>123456789</CreateTime><MsgType><![CDATA[event]]></MsgType><Event><![CDATA[subscribe]]></Event></xml>");
        $subscriber = Subscriber::where('openId', '1234567')->first();

        $this->assertResponseOk();
        $this->see('success');
        $this->assertNotNull($subscriber);
    }

    /**
     * Test the unsubscribe event
     *
     * @return void
     */
    public function testUnsubscribeOfficialAccount()
    {
        factory(App\Models\Subscriber::class)->create();
        $this->trigger("<xml><ToUserName><![CDATA[toUser]]></ToUserName><FromUserName><![CDATA[1234567]]></FromUserName><CreateTime>123456789</CreateTime><MsgType><![CDATA[event]]></MsgType><Event><![CDATA[unsubscribe]]></Event></xml>");

        $this->assertResponseOk();
        $this->see('success');
        $this->assertFalse(Subscriber::where('openId', 1234567)->first()->subscribed());
    }

    /**
     * Test that vendor scan the qr ticket on his own cms page to subscribe
     *
     * @return void
     */
    public function testVendorSubscribeToOfficialAccount()
    {
        $this->trigger("<xml><ToUserName><![CDATA[toUser]]></ToUserName><FromUserName><![CDATA[1234567]]></FromUserName><CreateTime>123456789</CreateTime><MsgType><![CDATA[event]]></MsgType><Event><![CDATA[subscribe]]></Event><EventKey><![CDATA[qrscene_weixinId]]></EventKey><Ticket><![CDATA[Ticket]]></Ticket></xml>");        
        
        $this->assertResponseOk();
        $this->see('success');
        $dummy = Subscriber::where('openId', 1234567)->first();
        $this->assertEquals('weixinId', $dummy->weixinId);
        $this->assertTrue($dummy->subscribed());
    }

    /**
     * Vendor first subscribe to account through regular channel and 
     * then scan the qr ticket on his cms own page. 
     *
     * @return void
     */
    public function testVendorSubscribeFirstAndScan()
    {
        $this->trigger("<xml><ToUserName><![CDATA[toUser]]></ToUserName><FromUserName><![CDATA[1234567]]></FromUserName><CreateTime>123456789</CreateTime><MsgType><![CDATA[event]]></MsgType><Event><![CDATA[subscribe]]></Event><EventKey><![CDATA[qrscene_regular]]></EventKey><Ticket><![CDATA[Ticket]]></Ticket></xml>");        
        $this->assertResponseOk();
        $this->see('success');
    }

    protected function trigger($xml)
    {
        $this->action('POST', 'MainController@postIndex', [], [], [], [], [], $xml);
    }
}
