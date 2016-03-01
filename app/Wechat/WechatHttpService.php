<?php
namespace App\Wechat;

use Cache;
use Carbon\Carbon;
use Log;
use App\Wechat\Exception\AccessTokenException;
use App\Wechat\Exception\RequestFailureException;

class WechatHttpService implements HttpServiceInterface
{
    const CACHE_KEY = 'WechatHttpService::access_token';

    /**
     * @var Http client
     */
    protected $client;

    public function __construct($client)
    {
        $this->client =  $client;
    }

    public function token()
    {
        return Cache::get(self::CACHE_KEY, '');
    }

    public function refresh()
    {
        $response = json_decode($this->client->request('GET', 'token', [
           'query' => [
               'grant_type' => 'client_crdential',
               'appid'      => env('WECHAT_APPID'),
               'secret'     => env('WECHAT_APP_SECRET')
           ]
        ])->getBody(), true);
        if (array_key_exists('access_token', $response)) {
            $expireAt = Carbon::now()->second($response['expires_in']);
            Cache::put(self::CACHE_KEY, $response['access_token'], $expireAt);
        } else {
            Log::error(__FUNCTION__ . " {$response['errmsg']}");
        }
    }

    protected function expired($code)
    {
        return $code === 40014 || $code === 42001;
    }

    public function request($method, $path, $body = [], $retry = true)
    {
        $this->token() === '' && $this->refresh();
        if ($this->token() === '') {
            throw new AccessTokenException;
        }

        $body = array_merge($body, ['query' => ['ACCESS_TOKEN' => $this->token()]]);
        $response = json_decode(
            $this->client->request($method, $path, $body)->getBody(),
            true
        );
        if (array_key_exists('errcode', $response) && $response['errcode'] !== 0) {
            if ($this->expired($response['errcode'])) {
                Cache::forget(self::CACHE_KEY);
                if ($retry) {
                    return $this->request($method, $path, $body, false);
                }
            } else {
                Log::error(__FUNCTION__ . " {$response['errmsg']}");
                throw new RequestFailureException;
            }
        } else {
            return $response;
        }
    }

}