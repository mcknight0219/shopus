<?php

namespace App\Console\Commands;

use App\Models\AccessToken;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use DB;

class RefreshToken extends Command
{
    /**
     * The anem and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refreshToken';

    protected $description = 'Refresh access token to weixin service';

    public function handle()
    {
        (new AccessToken)->forceRequest();   
    }
}