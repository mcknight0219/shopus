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

    public function testCreateUnknownType()
    {
        $this->setExpectedException(MessageFactoryException::class);
        $factory = new MessageFactory;
        $factory->create('unknown', []); 
    }

    public function testCreateOutboundText()
    {

    }

    public function testCreateInboundText()
    {
        $factory = new MessageFactory;
        $attributes = [
            'ToUserName'    => 'us',
            'FromUserName'  => 'client',
            'CreateTime'    => 1456370438,
            'MsgType'       => 'text',
            'Content'       => 'a simple message',
            'MsgId'         => 123456
        ];

        $factory->create('inbound', $attributes);
        $m = Message::where('toUserName', 'us')->first();
        
        $this->assertNotNull($m);
        $this->assertInstanceOf('App\Models\Message', $m);
        $this->assertInstanceOf('App\Models\Inbound', $m->messageable);

        $this->assertEquals($attributes['ToUserName'], $m->toUserName);
        $this->assertEquals($attributes['FromUserName'], $m->fromUserName);
        $this->assertEquals($attributes['MsgType'], $m->msgType);
        $this->assertEquals($attributes['MsgId'], $m->messageable->msgId);
        $content = json_decode($m->messageable->content);
        $this->assertEquals($attributes['Content'], $content->Content);

        $this->assertEquals($m->createTime);
    }

    public function testCreateInboundImage()
    {
        $factory = new MessageFactory;
        $attributes = [
            'ToUserName'    => 'us',
            'FromUserName'  => 'client',
            'CreateTime'    => 1456355029,
            'MsgType'       => 'image',
            'PicUrl'        => 'an image url',
            'MediaId'       => 123,
            'MsgId'         => 111111
        ];

        $factory->create('inbound', $attributes);
        $m = Message::where('msgType', 'image')->first();

        $this->assertNotNull($m);
        $this->assertInstanceOf('App\Models\Inbound', $m->messageable);

        $this->assertEquals($attributes['MsgType'], $m->msgType);
        $this->assertEquals($attributes['MsgId'], $m->messageable->msgId);
        $content = json_decode($m->messageable->content);
        $this->assertEquals($attributes['PicUrl'], $content->PicUrl);
        $this->assertEquals($attributes['MediaId'], $content->MediaId);
        $this->assertEquals(2, count(get_object_vars($content)));
    }
}
