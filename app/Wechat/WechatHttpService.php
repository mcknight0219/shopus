<?php

namespace App\Wechat;

use Cache;
use Carbon\Carbon;
use App\Wechat\Exception\AccessTokenException;
use App\Wechat\Exception\RequestFailureException;

class WechatHttpService implements HttpServiceInterface
{
    /**
     * @var string Access token cache key
     */
    protected $cacheKey = 'WechatHttpService::access_token';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * WechatHttpService constructor.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct($client)
    {
        $this->client =  $client;
    }

    /**
     * Read token that is cache already. Return empty string  
     * if none set or has already expired.
     *
     * @return string
     */
    public function token()
    {
        return Cache::get($this->cacheKey, '');
    }

    public function requestToken()
    {
        return collect(
            json_decode($this->client->request('GET', 'token', [
                'query' => [
                    'grant_type' => 'client_credential',
                    'appid' => env('WECHAT_APPID'),
                    'secret' => env('WECHAT_APP_SECRET')
                ]
            ])->getBody(), true)
        )->get('access_token', '');
    }

    /**
     * Put request token in cache if not already
     *
     * @param string $token
     */
    protected function rememberInCache($token)
    {
        if (! Cache::has($this->cacheKey)) {
            // Access token is valid for two hours
            Cache::put($this->cacheKey, $token, Carbon::now()->second(2 * 3600));
        }
    }

    protected function filter($code)
    {
        return $code === 40001 || $code === 42001 || $code === 40014;
    }

    /**
     * Request that automatically validate access_token and refresh it when necessary
     *
     * @param string $method
     * @param string $path
     * @param array  $body
     * @param bool   $retry
     * @return \Illuminate\Support\Collection
     *
     * @throws AccessTokenException
     * @throws RequestFailureException
     */
    public function request($method, $path, $body = [], $retry = true)
    {
        $token = $this->token() ?: $this->requestToken();
        if (! $token) {
            throw new AccessTokenException;
        }
        $this->rememberInCache($token);

        $body['query'] = ['ACCESS_TOKEN' => $token];
        $resp = collect(
            json_decode(
                $this->client->request($method, $path, $body)->getBody(),
                true
            )
        );

        if ($this->filter($resp->get('errcode'))) {
            if ($retry) {
                Cache::forget($this->cacheKey);
                unset($body['query']);
                return $this->request($method, $path, $body, false);
            }
            throw new AccessTokenException;
        }
        return $resp;
    }

}
