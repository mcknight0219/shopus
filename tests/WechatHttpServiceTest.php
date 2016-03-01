<?php

use App\Wechat\HttpServiceInterface;
use App\Wechat\WechatHttpService;
use App\Wechat\Exception\AccessTokenException;
use App\Wechat\Exception\RequestFailureException;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;


class WechatHttpServiceTest extends TestCase
{
    public function testRefresh()
    {
        $body1 = json_encode([
            'errcode' => 40014,
            'errmsg'  => 'expired access token'
        ]);

        $body2 = json_encode([
            'access_token'  => 123456,
            'expires_in'    => 7200
        ]);

        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], $body1),
            new Response(200, ['Content-Type' => 'application/json'], $body2)
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $service = new WechatHttpService($client);
        // check if cache is set after refresh
        $service->refresh();
        $this->assertEmpty($service->token());

        $service->refresh();
        $this->assertEquals(123456, $service->token());
    }

    public function testRequestSuccess()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token'  => 123456,
                'expires_in'    => 7200
            ])),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'errcode' => 0,
                'errmsg'  => 'ok'
            ]))
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $service = new WechatHttpService($client);

        try {
            $resp = $service->request('GET', '/', []);

        } catch(Exception $e) {
            echo $e->getMessage();
            $this->assertTrue(false, 'Exception are not expected thrown here');
        }
        $this->assertArrayHasKey('errcode', $resp);
        $this->assertArrayHasKey('errmsg', $resp);
    }

    public function testRequestAccessTokenFailure()
    {
        $this->setExpectedException(AccessTokenException::class);
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'errcode' => 40001,
                'errmsg'  => 'app secret invalid'
            ]))
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $service = new WechatHttpService($client);
        $service->request('GET', '/', []);
    }

    public function testRequestGeneralFailure()
    {
        $this->setExpectedException(RequestFailureException::class);
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token' => 123456,
                'expires_in'   => 7200
            ])),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'errcode'   => 40002,
                'errmsg'    => 'invalid grant type'
            ]))
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $service = new WechatHttpService($client);
        $service->request('GET', '/', []);
    }

    public function testRrefreshTokenAfterExpiry()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token'  => 123456,
                'expires_in'    => 7200
            ])),
            new Response(200, ['Content-Type' => 'applciation/json'], json_encode([
                'errcode' => 40014,
                'errmsg'  => 'access token expired'
            ])),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token'  => 654321,
                'expires_in'    => 7200
            ])),
            new Response(200, ['Content-Type' => 'aplication/json'], json_encode([
                'errcode' => 0,
                'errmsg' => 'ok'
            ]))
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $service = new WechatHttpService($client);
        $service->request('GET', '/', []);
    }

    public function testServiceProvider()
    {
        $service = App::make('App\Wechat\HttpServiceInterface');
        $this->assertInstanceOf('App\Wechat\WechatHttpService', $service);
        // make sure wechat service is singleton
        $this->assertSame($service, App::make('App\Wechat\HttpServiceInterface'));
    }
}