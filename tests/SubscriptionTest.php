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
        $response = $this->trigger("<xml><ToUserName><![CDATA[toUser]]></ToUserName><FromUserName><![CDATA[1234567]]></FromUserName><CreateTime>123456789</CreateTime><MsgType><![CDATA[event]]></MsgType><Event><![CDATA[subscribe]]></Event></xml>");
        $subscriber = Subscriber::where('openId', '1234567')->first();

        $this->assertResponseOk();
        $this->assertEquals('application/xml', $response->headers->get('Content-Type'));
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

    protected function trigger($xml)
    {
        return $this->action('POST', 'MainController@postIndex', [], [], [], [], [], $xml);
    }
}
