<?php

namespace App\Providers;

use App\Wechat\WechatHttpService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Wechat\HttpServiceInterface', function($app) {
            $client = new Client(['base_uri' => 'https://api.weixin.qq.com/cgi-bin/']);
            return new WechatHttpService($client);
        });
    }
}
