<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\MessageFactory;
use App\Models\Inbound;
use App\Models\Message;
use App\Models\Outbound;
use App\Models\Event;
use App\Exceptions\MessageFactoryException;

class MessageFactoryTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateOutboundText()
    {
        $attributes = [
            'ToUserName'    => 'toUser',
            'FromUserName'  => 'fromUser',
            'CreateTime'    => 12345678,
            'MsgType'       => 'text',
            'Content'       => '你好'
        ];

        $m = (new MessageFactory)->create($attributes, 'outbound');
        $this->assertNotNull($m);
        $this->assertInstanceOf('App\Models\Message', $m);
        $this->assertInstanceOf('App\Models\Outbound', $m->messageable);

        $content = $m->messageable->content;
        $this->assertEquals($attributes['Content'], $content['Content']);
        $this->assertEquals($attributes['MsgType'], $m->msgType);
    }

    public function testCreateOutboundImage()
    {
        $attributes = [
            'ToUserName'    => 'toUser',
            'FromUserName'  => 'fromUser',
            'CreateTime'    => 12345678,
            'MsgType'       => 'image',
            'MediaId'       => 'media_id'
        ];

        $m = (new MessageFactory)->create($attributes, 'outbound');
        $this->assertNotNull($m);
        $this->assertInstanceOf('App\Models\Message', $m);
        $this->assertInstanceOf('App\Models\Outbound', $m->messageable);

        $content = $m->messageable->content;
        $this->assertEquals($attributes['MediaId'], $content['MediaId']);
        $this->assertEquals($attributes['MsgType'], $m->msgType);
    }

    public function testCreateOutboundReverseRelation()
    {
        $attributes = [
            'ToUserName'    => 'toUser',
            'FromUserName'  => 'fromUser',
            'CreateTime'    => 12345678,
            'MsgType'       => 'text',
            'Content'       => '你好'
        ];

        $m = (new MessageFactory)->create($attributes,'outbound');
        $o = $m->messageable;
        $this->assertInstanceOf('App\Models\Message', $o->message);
        $this->assertEquals($attributes['MsgType'], $m->msgType);
    }

    public function testCreateOutboundXml()
    {
        $attributes = [
            'ToUserName'    => 'toUser',
            'FromUserName'  => 'fromUser',
            'CreateTime'    => 12345678,
            'MsgType'       => 'text',
            'Content'       => '你好'
        ];

        $m = (new MessageFactory)->create($attributes, 'outbound');
        $o = $m->messageable;
        $xmlStr = $o->toXml();
        $xml = simplexml_load_string($xmlStr, 'SimpleXMLElement', LIBXML_NOCDATA);

        $arr = json_decode(json_encode((array)$xml), TRUE);
        $this->assertEquals($attributes['CreateTime'], intval($arr['CreateTime']));
        $this->assertEquals($attributes['Content'], $arr['Content']);
    }

    public function testCreateOutboundArticleAdd()
    {
        $attributes = [
            'ToUserName'    => 'toUser',
            'FromUserName'  => 'fromUser',
            'CreateTime'    => 12345678,
            'MsgType'       => 'news'
        ];

        $m = (new MessageFactory)->create($attributes, 'outbound');
        $this->assertEquals('news', $m->msgType);

        $o = $m->messageable;
        
        // add article
        $o->addArticle('title1', 'description1', 'picurl1', 'url');
        $this->assertEquals(1, $o->numArticles());

        $articles = $o->articles();
        $article = $articles[0];
        $this->assertEquals('title1', $article['Title']);
        $this->assertEquals('description1', $article['Description']);
        $this->assertEquals('picurl1', $article['PicUrl']);
        $this->assertEquals('url', $article['Url']);
    }

    public function testCreateOutboundArticleDelete()
    {
        $attributes = [
            'ToUserName'    => 'toUser',
            'FromUserName'  => 'fromUser',
            'CreateTime'    => 12345678,
            'MsgType'       => 'news'
        ];
        $m = (new MessageFactory)->create($attributes, 'outbound');
        $o = $m->messageable;

        $o->addArticle('title1', 'description1', 'picurl1', 'url');
        $this->assertEquals(1, $o->numArticles());

        // delete non-existent article
        $o->deleteArticle('title2');
        $this->assertEquals(1, $o->numArticles());

        $o->deleteArticle('title1');
        $this->assertEquals(0, $o->numArticles());
    }

    public function testCreateOutboundArticleXML()
    {
        $attributes = [
            'ToUserName'    => 'toUser',
            'FromUserName'  => 'fromUser',
            'CreateTime'    => 12345678,
            'MsgType'       => 'news'
        ];
        $m = (new MessageFactory)->create($attributes, 'outbound');
        $o = $m->messageable;
        $o->addArticle('title1', 'description1', 'picurl1', 'url1');
        $o->addArticle('title2', 'description2', 'picurl2', 'url2');
        $this->assertEquals(2, $o->numArticles());

        $xmlStr = $o->toXml();
        $xml = simplexml_load_string($xmlStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $arr = json_decode(json_encode((array)$xml), TRUE);

        $this->assertEquals(2, $arr['ArticleCount']);
        $article1 = $arr['Articles']['item'][0];
        $this->assertEquals('title1', $article1['Title']);
        $article2 = $arr['Articles']['item'][1];
        $this->assertEquals('title2', $article2['Title']);
    }

    /**
     * Event message
     */
    public function testCreateEventSubscribe()
    {
        $factory = new MessageFactory;
        $attributes = [
            'ToUserName'    => 'toUser',
            'FromUserName'  => 'fromUser',
            'CreateTime'    => 1456370438,
            'MsgType'       => 'event',
            'Event'         => 'subscribe'
        ];

        $factory->create($attributes, 'event');
        $m = Message::where('msgType', 'event')->first();
        $this->assertNotNull($m);
        $this->assertInstanceOf('App\Models\Message', $m);
        $this->assertInstanceOf('App\Models\Event', $m->messageable);

        $this->assertEquals($attributes['MsgType'], $m->msgType);
        $this->assertEquals($attributes['Event'], $m->messageable->event);
        $this->assertNull($m->messageable->eventKey);
        $this->assertNull($m->messageable->ticket);
    }

    public function testCreateEventLOCATION()
    {
        $factory = new MessageFactory;
        $attributes = [
            'ToUserName'    => 'toUser',
            'FromUserName'  => 'fromUser',
            'CreateTime'    => 1456370438,
            'MsgType'       => 'event',
            'Event'         => 'LOCATION',
            'Latitude'      => 23.137466,
            'Longitude'     => 113.352425,
            'Precision'     => 119.385040
        ];

        $factory->create($attributes, 'event');
        $m = Message::where('msgType', 'event')->first();
        $this->assertInstanceOf('App\Models\Message', $m);
        $this->assertInstanceOf('App\Models\Event', $m->messageable);

        $content = explode(';', $m->messageable->eventKey);
        $this->assertEquals(3, count($content));
        $this->assertEquals($attributes['Latitude'], $content[0]);
        $this->assertEquals($attributes['Longitude'],$content[1]);
    }

    /**
     * Inbound message
     */
    public function testCreateInboundText()
    {
        $factory = new MessageFactory;
        $attributes = [
            'ToUserName'    => 'toUser',
            'FromUserName'  => 'fromUser',
            'CreateTime'    => 1456370438,
            'MsgType'       => 'text',
            'Content'       => 'a simple message',
            'MsgId'         => 123456
        ];

        $factory->create($attributes, 'inbound');
        $m = Message::where('msgType', 'text')->first();
        
        $this->assertNotNull($m);
        $this->assertInstanceOf('App\Models\Message', $m);
        $this->assertInstanceOf('App\Models\Inbound', $m->messageable);

        $this->assertEquals($attributes['ToUserName'], $m->toUserName);
        $this->assertEquals($attributes['FromUserName'], $m->fromUserName);
        $this->assertEquals($attributes['MsgType'], $m->msgType);
        $this->assertEquals($attributes['MsgId'], $m->messageable->msgId);
        $content = $m->messageable->content;
        $this->assertEquals($attributes['Content'], $content['Content']);
    }

    public function testCreateInboundImage()
    {
        $factory = new MessageFactory;
        $attributes = [
            'ToUserName'    => 'toUser',
            'FromUserName'  => 'fromUser',
            'CreateTime'    => 1456355029,
            'MsgType'       => 'image',
            'PicUrl'        => 'an image url',
            'MediaId'       => 123,
            'MsgId'         => 111111
        ];

        $factory->create($attributes, 'inbound');
        $m = Message::where('msgType', 'image')->first();

        $this->assertNotNull($m);
        $this->assertInstanceOf('App\Models\Inbound', $m->messageable);

        $this->assertEquals($attributes['MsgType'], $m->msgType);
        $this->assertEquals($attributes['MsgId'], $m->messageable->msgId);
        $content = $m->messageable->content;
        $this->assertEquals($attributes['PicUrl'], $content['PicUrl']);
        $this->assertEquals($attributes['MediaId'], $content['MediaId']);
        $this->assertEquals(2, count($content));
    }
}
