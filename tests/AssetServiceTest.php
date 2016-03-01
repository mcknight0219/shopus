<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class AssetServiceTest extends TestCase
{
    public function testServiceProvider()
    {
        $service = App::make('AssetService');
        $this->assertInstanceOf('App\Wechat\AssetService', $service);
        $this->assertSame($service, App::make('AssetService'));
    }

    protected function createAssetService($httpResponseMockArray)
    {
        $mock = new MockHandler($httpResponseMockArray);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $httpService = new App\Wechat\WechatHttpService($client);
        $assetService = new App\Wechat\AssetService($httpService);

        return $assetService;
    }

    public function testCount()
    {
        $assetService = $this->createAssetService([
            new Response(200, ['Content-Type' => 'applciation/json'], json_encode([
                'access_token'  => 123456,
                'expires_in'    => 7200
            ])),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'voice_count' => 1,
                'video_count' => 2,
                'image_count' => 3,
                'news_count'  => 4
            ]))
        ]);

        $this->assertEquals(4, $assetService->count('news'));
    }

    public function testBatch()
    {
        $assetService = $this->createAssetService([
            new Response(200, ['Content-Type' => 'applciation/json'], json_encode([
                'access_token'  => 123456,
                'expires_in'    => 7200
            ])),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'total_count' => 2,
                'item_count'  => 2,
                'item' => [
                    [
                        'media_id'  => 1,
                        'name'      => 'NAME',
                        'update_time'   => 'UPDATE_TIME',
                        'url'       => 'URL'
                    ],
                    [
                        'media_id'  => 2,
                        'name'      => 'NAME',
                        'update_time'   => 'UPDATE_TIME',
                        'url'       => 'URL'
                    ]
                ]
            ])),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'total_count'   => 2,
                'item_count'    => 1,
                'item' => [
                    [
                        'media_id'  => 1,
                        'name'      => 'NAME',
                        'update_time'   => 'UPDATE_TIME',
                        'url'       => 'URL'
                    ]
                ]
            ])),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'total_count'   => 2,
                'item_count'    => 1,
                'item' => [
                    [
                        'media_id'  => 1,
                        'name'      => 'NAME',
                        'update_time'   => 'UPDATE_TIME',
                        'url'       => 'URL'
                    ]
                ]
            ]))
        ]);

        $list = $assetService->batch('image');
        $this->assertEquals(2, count($list));
        $this->assertEquals('NAME', $list[1]['name']);

        $list = $assetService->batch('image');
        $this->assertEquals(2, count($list));
    }

    public function testUpload()
    {
        $assetService = $this->createAssetService([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token'  => 123456,
                'expires_in'    => 7200
            ])),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'type' => 'image',
                'media_id' => 123,
                'created_at' => 123456789
            ]))
        ]);

        $this->assertEquals(123, $assetService->upload('image', 'tests/sample/test.png'));
    }
}
