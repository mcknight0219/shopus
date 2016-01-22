<?php
namespace App\Models

use GuzzleHttp\Client;
use Log;
use DB;
use Cache;

class AccessToken 
{
    const ACCESS_TOKEN_KEY = '@accessToken';

    public function get()
    {
        $token = Cache::get(self::ACCESS_TOKEN_KEY);
        return  is_null($token) ? '' : $token;
    }

    // Forcifully request token from service and save
    // it to database. Mainly called from task scheduling
    public function forceRequest()
    {
        $this->_requestAccessToken();
    }

    private function _requestAccessToken()
    {
        $secrets = DB::select('select * from appSecrets');
        $client = new Client(['base_uri' => 'https://api.weixin.qq.com/cgi-bin']);
        $resp = json_decode($client->request('GET', 'token', [
            'query' => [
                'grant_type' => 'client_credential',
                'appid' =>  $secrets['appId'],
                'secret' => $secrets['appSecret'] 
            ]
        ])->getBody(), true);

        if( array_key_exists('access_token', $resp) ) {
            $token = $resp['access_token'];
            Cache::set(self::ACCESS_TOKEN_KEY, $token);
            // give 5 seconds room so we are not caught in between
            Cache::expire(self::ACCESS_TOKEN_KEY, intval($resp['expires_in']));
        } else {
            LOG::error('AccessToken: [' . $resp['errcode'] . '] ' . $resp['errmsg']);
        }
    }
} 