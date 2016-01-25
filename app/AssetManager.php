<?php
namespace App;

use GuzzleHttp\Client;
use Log;
use App\Models\AccessToken;


class ErrorResponseException extends Exception;

// We don't need to store assets or meta in our database because
// weixin will store and deliver them as long as we have MediaId 
class AssetManager
{
    /**
     * Http client
     * 
     * @var GuzzleHttp\Client
     */
    protected $client;

    protected $token;

    function __construct()
    {
        $client = new Client(['baseUri' => 'https://api.weixin.qq.com/cgi-bin/material']);
        $token = new AccessToken;
    }

    protected function guardResponse($response)
    {
        if( array_key_exists('errcode', $response) ) {
            Log::error("Error in response: ${response['errmsg']}");
            throw new ErrorResponseException;
        }
    }

    // Count of permanent assets.
    // upper limits for image and news are 5000.
    // upper limits for others are 1000.
    public function assetsCount()
    {
        try {
            $resp = json_decode($client->request('GET', 'get_materialcount', [
                'query' => [
                    'access_token' => $token->get()
                ]
            ])->getBody(), true);
            guardResponse($resp);
            return $resp;
        } catch(ErrorResponseException $e) {
            return [];
        } catch(Exception $e) {
            Log::error('Http request error: ' . $e->getMessage());
            return [];
        }
    }

    public function assetList($type)
    {
        $lists = [];
        if( !in_array(strtolower($type), ['image', 'video', 'voice', 'news']) ) {
            Log::error("Unknown asset type: ${type}");
            return $lists;
        }

        try {
            $offset = 0; $total = 0;
            while (true) {
                $resp = client->request('POST', 'batchget_material', [
                    'query' => ['access_token' => $token->get()],
                    'body'  => [
                        'type'  => $type,
                        'offset'=> $offset,
                        'count' => 20
                    ]
                ]);
                $decoded = json_decode($resp->getBody(), true);
                guardResponse($decoded);

                $count = $decoded['item_count'];
                $total += $count;
                if( $total === $decoded['total_count'] ) break;
                else $offset = $total;

                $lists = array_merge($lists, $decoded['item']);
            }
        } catch(ErrorResponseException $e) {
            $lists =  [];
        } catch(Exception $e) {
            Log::error('Http request error: ' . $e->getMessage());
            $lists =  [];
        } finally {
            return $lists;
        }
    }

    // for temporaray asset. Return array of response
    public function upload($type, $mediaData)
    {
        $info = [];
        if( !is_string($type) || !in_array($type, ['image', 'voice', 'video', 'thumb']) ) {
            Log::error("Unknown asset type: ${type}");
            return
        }
    }
}