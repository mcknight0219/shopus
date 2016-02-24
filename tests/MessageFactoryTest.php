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

    public function testCreateInbound()
    {
        $factory = new MessageFactory;
        $attributes = [
            'ToUserName'    => 'us',
            'FromUserName'  => 'client',
            'CreateTime'    => 1456355029,
            'MsgType'       => 'text',
            'Content'       => 'a simple message',
            'MsgId'         => 123456
        ];

        $factory->create('inbound', $attributes);
        $m = Message::where('toUserName', 'us')->first();
        
        $this->assertNotNull($m);
    }
}
