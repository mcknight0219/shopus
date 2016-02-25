<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\MessageFactory;
use App\GrandDispatcher;

class GrandDispatcherTest extends TestCase
{
    use DatabaseTransactions;

    public function testUserSubscribe()
    {
        $m = (new MessageFactory)->create('event', [
            'ToUserName'    => 'toUser',
            'FromUserName'  => 'fromUser',
            'CreateTime'    => 12345678,
            'MsgType'       => 'event',
            'Event'         => 'subscribe'
        ]);
        
        $dispatcher = new GrandDispatcher;
        $dispatcher->handle($m); 
    }
}
