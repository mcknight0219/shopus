<?php
namespace App;

use GuzzleHttp\Client;
use Log;
use App\Models\AccessToken;

class AssetManager
{
    /**
     * Http client
     * 
     * @var GuzzleHttp\Client
     */
    protected $client;

    function __construct()
    {
        $client = new Client(['baseUri' => '']);
    }

    // Ask API how many material assets we have store remotely
    public function assetsCount()
    {

    }     
}